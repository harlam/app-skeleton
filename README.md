- Create project
`composer create-project harlam/app-skeleton my-project`

- Copy `.env.dist` to `.env` and edit

- Build docker image
`docker-compose build`

- Run container
`docker-compose up`

- Init project
```docker.sh init``` or with composer args ```docker.sh init --no-dev```

- Nginx example config
```
server {
    listen 80;
    server_name my-project.local;

    set $app_fpm "127.0.0.1:9009";
    set $host_path "/projects/my-project";
    set $container_path "/var/www/html";
    set $app_path "/public";

    root $host_path$app_path;

    charset utf-8;

    location / {
        index index.php;
        try_files $uri $uri/ /index.php?$args;
    }

    error_log /var/log/nginx/my-project.local.error.log;
    access_log /var/log/nginx/my-project.local.access.log;

    location ~ \.php {
        fastcgi_split_path_info ^(.+\.php)(.*)$;

        set $fsn /index.php;
        if (-f $container_path$app_path$fastcgi_script_name){
            set $fsn $fastcgi_script_name;
        }
        include /etc/nginx/fastcgi.conf;
        fastcgi_pass $app_fpm;
        fastcgi_param  SCRIPT_FILENAME  $container_path$app_path$fsn;
    }
}
```
