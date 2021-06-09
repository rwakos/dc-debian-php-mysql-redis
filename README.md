# Docker Compose for Debian, PHP 7.4, MySQL, and Redis

Basic docker compose setup for Debian buster (find here: https://hub.docker.com/repository/docker/richardreveron1/debian)


### Running it
Rename your `.env.example` to just `.env`, add your values inside, and copy it to the `html` folder.

Then run:

`docker-compose build && docker-compose up -d`

Go to your browser and go to:

`localhost`

If you get at the end the message: `seems to be working` you are good to go, if not, check the info on the `.ENV` file.