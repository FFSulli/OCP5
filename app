#!/usr/bin/env bash

# declare function(s)
displayUsage() {
    echo -e "\e[0;32mUsage bash app <command> <args> \e[m"
    echo -e "\e[0;32m<command>\e[m : \e[0;33m<start|stop|connect|install|update|fixtures|cache|test|console|command|sync>\e[m"
    echo -e "   - \e[0;33mstart\e[m: start docker stack"
    echo -e "   - \e[0;33mstop\e[m: stop docker stack"
    echo -e "   - \e[0;33mconnect\e[m: connect to the php container"
    echo -e "   - \e[0;33minstall\e[m: run composer install"
    echo -e "   - \e[0;33mupdate\e[m: run composer update"
    echo -e "   - \e[0;33mfixtures\e[m: reload fixtures from scratch"
    echo -e "   - \e[0;33mcache\e[m: clear the symfony cache"
    echo -e "   - \e[0;33mtest\e[m: run the tests"
    echo -e "   - \e[0;33mconsole\e[m: display the symfony console"
    echo -e "   - \e[0;33mcommand\e[m: execute any command you want with quotes"
    echo -e "   - \e[0;33mdoc\e[m: open firefox browser and navigate to api doc"
    echo -e "   - \e[0;33mpma\e[m: open firefox browser and navigate to phpmyadmin"
    echo -e "   - \e[0;33msync\e[m: sync volumes"
    echo -e "   - \e[0;33mmount_gfs\e[m: Mount GFS volume /!\ BE CAREFUL PRODUCTION VOLUME"
    echo -e "   - \e[0;33mumount_gfs\e[m: Umount GFS volume /!\ BE CAREFUL PRODUCTION VOLUME"
    echo -e "   - \e[0;33mmessenger\e[m: launch all messenger queues and output in var/log/messenger"
    echo -e "   - \e[0;33mconsumer_stop\e[m: Stop all non php supervisor consumer"
    echo -e "   - \e[0;33mconsumer_start\e[m: Start all supervisor consumer"
    echo -e "   - \e[0;33mconsumer_status\e[m: Return consumer status"
    echo -e "\e[0;32m<args>\e[m : Additional arguments are passed here"
}

# check input first argument
if [[ -z "$1" ]]; then
    displayUsage
    exit 1
fi

# define constants
PHP_CONTAINER_NAME="oc_blog_php"
DOCKER_EXEC_BASE_COMMAND="docker exec -it "

# cd to this script path
CURRENT_PATH=$(readlink -f $0 | xargs dirname)
cd ${CURRENT_PATH}

# execute command
command=$1
argv="${@:2}"
if [[ ${command} == "start" ]]; then
    export LDAP_CONF_FILE=`cat /etc/os-release | grep -q 'ID=ubuntu' && echo "/etc/ldap.conf" || echo "/etc/ldap/ldap.conf"`
    docker-compose up ${argv}
    exit 0
elif [[ ${command} == "stop" ]]; then
    export LDAP_CONF_FILE=`cat /etc/os-release | grep -q 'ID=ubuntu' && echo "/etc/ldap.conf" || echo "/etc/ldap/ldap.conf"`
    docker-compose down ${argv}
    exit 0
elif [[ ${command} == "connect" ]]; then
    ${DOCKER_EXEC_BASE_COMMAND} ${PHP_CONTAINER_NAME} bash ${argv}
    exit 0
elif [[ ${command} == "install" ]]; then
    ${DOCKER_EXEC_BASE_COMMAND} ${PHP_CONTAINER_NAME} composer install ${argv}
    exit 0
elif [[ ${command} == "update" ]]; then
    ${DOCKER_EXEC_BASE_COMMAND} ${PHP_CONTAINER_NAME} php -d memory_limit=-1 /usr/bin/composer update ${argv}
    exit 0
elif [[ ${command} == "fixtures" ]]; then
    ${DOCKER_EXEC_BASE_COMMAND} ${PHP_CONTAINER_NAME} bin/console doctrine:schema:drop --force
    ${DOCKER_EXEC_BASE_COMMAND} ${PHP_CONTAINER_NAME} bin/console doctrine:schema:create
    ${DOCKER_EXEC_BASE_COMMAND} ${PHP_CONTAINER_NAME} bin/console doctrine:fixtures:load --no-interaction
    exit 0
elif [[ ${command} == "build" ]]; then
    ct_name="$2"
    moreArgs="${@:3}"

    docker rm --volumes ${ct_name}
    docker build ./ --pull -t ${ct_name}
    docker run ${moreArgs} --name ${ct_name} ${ct_name}
    exit 0
elif [[ ${command} == "cache" ]]; then
    ${DOCKER_EXEC_BASE_COMMAND} ${PHP_CONTAINER_NAME} bin/console cache:clear ${argv}
    exit 0
elif [[ ${command} == "test" ]]; then
    ${DOCKER_EXEC_BASE_COMMAND} ${PHP_CONTAINER_NAME} bin/phpunit ${argv}
    exit 0
elif [[ ${command} == "console" ]]; then
    ${DOCKER_EXEC_BASE_COMMAND} ${PHP_CONTAINER_NAME} bin/console ${argv}
    exit 0
elif [[ ${command} == "command" ]]; then
    ${DOCKER_EXEC_BASE_COMMAND} ${PHP_CONTAINER_NAME} ${argv}
    exit 0
elif [[ ${command} == "sync" ]]; then
    ${DOCKER_EXEC_BASE_COMMAND} ${PHP_CONTAINER_NAME} sync ${argv}
    exit 0
elif [[ ${command} == "mount_gfs" ]]; then
    ${DOCKER_EXEC_BASE_COMMAND} ${PHP_CONTAINER_NAME} mount_gfs_prod
    exit 0
elif [[ ${command} == "umount_gfs" ]]; then
    ${DOCKER_EXEC_BASE_COMMAND} ${PHP_CONTAINER_NAME} umount_gfs_prod
    exit 0
elif [[ ${command} == "doc" ]]; then
    port=$(cat docker-compose.yml | awk -F"_php" -v RS="" '{print $3}' | grep -Po '\- \d+:8000' | sed -E 's/\- ([0-9]+):8000/\1/g')
    google-chrome "http://localhost:${port}/api/doc" &
    exit 0
elif [[ ${command} == "pma" ]]; then
    port=$(cat docker-compose.yml | awk -F"_phpmyadmin" -v RS="" '{print $3}' | grep -Po '\- \d+:80' | sed -E 's/\- ([0-9]+):80/\1/g')
    google-chrome "http://localhost:${port}" &
    exit 0
elif [[ ${command} == "messenger" ]]; then
    mkdir -p var/log/messenger
    queueNames=($(cat config/packages/messenger.yaml | grep "queue_name" | sed "s/queue_name://g" | sed -e 's/^[[:space:]]*//'))
    for queueName in "${queueNames[@]}"; do
        ${DOCKER_EXEC_BASE_COMMAND} --detach ${PHP_CONTAINER_NAME} sh -c "bin/console messenger:consume ${queueName} -vv --sleep=5 > var/log/messenger/${queueName}.log 2>&1"
    done
    exit 0
elif [[ ${command} == "consumer_stop" ]]; then
    ${DOCKER_EXEC_BASE_COMMAND} ${PHP_CONTAINER_NAME} supervisorctl stop backup_handler:*
    ${DOCKER_EXEC_BASE_COMMAND} ${PHP_CONTAINER_NAME} supervisorctl stop maintenance_handler:*
    ${DOCKER_EXEC_BASE_COMMAND} ${PHP_CONTAINER_NAME} supervisorctl stop scaling_handler:*
    ${DOCKER_EXEC_BASE_COMMAND} ${PHP_CONTAINER_NAME} supervisorctl start php-fpm
    exit 0
elif [[ ${command} == "consumer_start" ]]; then
    ${DOCKER_EXEC_BASE_COMMAND} ${PHP_CONTAINER_NAME} supervisorctl start all
    exit 0
elif [[ ${command} == "consumer_status" ]]; then
    ${DOCKER_EXEC_BASE_COMMAND} ${PHP_CONTAINER_NAME} supervisorctl status all
    exit 0
else
    echo "Unknown command ${command}"
    exit 1
fi
