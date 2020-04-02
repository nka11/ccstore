Build docker file
```
docker build -t cc .
```

Launch docker
```
docker run -d --rm -p 2080:80 --name cc_app -v "$PWD":/var/www/html cc
```
