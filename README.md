Build docker file
```
docker build -t cc .
```

Launch docker
```
docker run -d --rm -p 2080:80 --name my-apache-php-app -v "$PWD":/var/www/html cc
```
