<?php
function getDatabaseConfig(): array
{
    return [
        "database" => [
            "test" => [
                "url" => "mysql:host=localhost:3306;dbname=php_login_management_gue_test",
                "username" => "root",
                "password" => ""
            ],
            "prod" => [
                "url" => "mysql:host=localhost:3306;dbname=php_login_management_gue",
                "username" => "root",
                "password" => ""
            ]
        ]
    ];
}