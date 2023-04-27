# Запуск
```shell
docker-compose up
```

Для запуска миграции:
```shell
docker-compose exec php php bin/console doctrine:migrations:migrate
```

Для заполнения данными:
```shell
docker-compose exec php php bin/console app:get-movies
```




