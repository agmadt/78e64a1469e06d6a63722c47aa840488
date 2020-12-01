# System Design
The application is developed with KISS, DRY and Fail-fast principle. 

The application is developed with restrictions based on a requirements, such as:
1. Using framework is not allowed but feel free to use Composer
2. Must be authenticated or authorized to use the API
3. Using worker or queue or message distribution
4. Store all sent message/email into database

Based on points above:
1. Point number `2` authorization is using OAuth2
2. Point number `3` queued using RabbitMQ and using `supervisord` to manage `worker`
3. Point number `4` stored into postgresql database

#### Note
There are several missing configuration from `docker-compose.yml` environment variable and you need to fill it first in order to use the application. Such as
1. SMTP account (can use google or sendgrid or anything) that allows sending emails

#### How to deploy
1. Clone the repository
2. Run `composer install`
3. Configuring `docker-compose.yml` environment variable file in `.docker.env`
4. Run `docker-compose build`
5. Run `docker-compose up -d`

#### How to send email
You can access the API specification in `localhost:8003` -- its a swagger-ui. Because the application is using OAuth2 Authorization Code Grant, there are several things you must do in order to be able to send email/hit the API
1. Hit `/authorize` endpoint (using postman or similiar program) and get the `authorization_code` value -- The required params are all documented in swagger-ui
2. Hit `/access_token` endpoint (using postman or similiar program) and pass the `authorization_code` and get `access_token` value -- the required parameter are all documented in swagger-ui
3. Put `access_token` in header with `Bearer {access_token}` format, and then hit `/emails/send` endpoint. -- The required parameter are all documented in swagger-ui

#### Application flow
![Application flow image](https://raw.githubusercontent.com/agmadt/78e64a1469e06d6a63722c47aa840488/master/docs/flow.png?token=AAJLBO2ZKCH5MTEE47OE53S7Z4KCM)
