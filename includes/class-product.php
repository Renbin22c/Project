<?php

class Product
{
    /**
     * Retrieve all users from database
     */
    public static function getAllProducts()
    {
        return DB::Connect()->select(
            'SELECT * FROM products ORDER BY id DESC',
            [],
            true
        );
    }

    /**
     * Retrieve product data by id
     */
    public static function getProductById($product_id)
    {
        return DB::connect()->select(
            'SELECT * FROM products WHERE id = :id',
            [
                'id' => $product_id
            ]
        );
    }

    public static function getProductNameById($product_id)
    {
        return DB::connect()->select(
            'SELECT 
            products.id,
            products.name,
            comments.product_id
            FROM comments
            JOIN products
            ON comments.product_id = products.id
            WHERE product_id = :product_id
            GROUP BY products.id',
            [
                'product_id' => $product_id
            ]
        );
    }

    /**
     * Retrieve all the publish products
     */
    public static function getPublishProducts()
    {
        return DB::connect()->select(
            'SELECT * FROM products WHERE status = :status ORDER BY id DESC',
            [
                'status' => 'publish'
            ],
            true
        );
    }

    public static function add( $name, $price, $type, $img_url, $release_time, $developer, $publisher ,$introduce)
    {
        return DB::connect()->insert(
            'INSERT INTO products ( name, price, type, img_url, release_time, developer, publisher, introduce )
            VALUES ( :name, :price, :type, :img_url, :release_time, :developer, :publisher, :introduce )',
            [
                'name' => $name,
                'price' => $price,
                'type' => $type,
                'img_url' => $img_url,
                'release_time' => $release_time,
                'developer' => $developer,
                'publisher' => $publisher,
                'introduce' => $introduce
            ]
        );
    }

    public static function update( $id, $name, $price, $type, $status, $img_url, $release_time, $developer, $publisher, $introduce)
    {
        // setup params
        $params = [
            'id' => $id,
            'name' => $name,
            'price' => $price,
            'type' => $type,
            'status' => $status,
            'img_url' => $img_url,
            'release_time' => $release_time,
            'developer' => $developer,
            'publisher' => $publisher,
            'introduce' => $introduce

        ];


        // update product data into the database
        return DB::connect()->update(
            'UPDATE products SET name = :name, price = :price, type = :type, status = :status, img_url = :img_url, 
            release_time = :release_time, developer = :developer, publisher = :publisher, introduce = :introduce WHERE id = :id',
            $params
        );

    }

    public static function delete ( $product_id )
    {
        return DB::connect()->delete(
            'DELETE FROM products where id = :id',
            [
                'id' => $product_id
            ]
        );
    }
}