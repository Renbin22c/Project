<?php

class Comment
{
    public static function getAllComments($user_id)
    {
        if ( Authentication::isUser() ) {
            return DB::connect()->select(
                'SELECT * FROM comments WHERE user_id = :user_id ORDER BY id DESC',
                [
                    'user_id' => $user_id
                ],
                true
            );
        }
        
        return DB::Connect()->select(
            'SELECT * FROM comments ORDER BY id DESC',
            [],
            true
        );
    }

    public static function getCommentById($comment_id)
    {
        return DB::connect()->select(
            'SELECT * FROM comments WHERE id = :id',
            [
                'id' => $comment_id
            ]
        );
    }

    public static function getCommentByUserId($user_id)
    {
        return DB::connect()->select(
            'SELECT * FROM comments WHERE user_id = :user_id',
            [
                'user_id' => $user_id
            ]
        );
    }

    public static function getCommentByProductId($product_id)
    {
        return DB::connect()->select(
            'SELECT * FROM comments WHERE product_id = :product_id',
            [
                'product_id' => $product_id
            ],
            true,
        );

    }
    


    public static function add( $commnet, $recommend, $user_id, $product_id)
    {
        return DB::connect()->insert(
            'INSERT INTO comments (comment, recommend, user_id, product_id)
            VALUES (:comment, :recommend, :user_id, :product_id)',
            [
                'comment' => $commnet,
                'recommend' => $recommend,
                'user_id' => $user_id,
                'product_id' => $product_id
            ]
        );
    }

    public static function delete ( $comment_id )
    {
        return DB::connect()->delete(
            'DELETE FROM comments where id = :id',
            [
                'id' => $comment_id
            ]
        );
    }

}

?>