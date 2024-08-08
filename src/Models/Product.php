<?php

namespace App\Models;

use PDO;
use PDOException;
use Exception;

class Product extends AbstractModel
{
    public static function getAll()
    {
        $query = "
        SELECT 
    p.id,
    p.name,
    p.in_stock,
    p.description,
    p.category,
    p.brand,
    (SELECT JSON_ARRAYAGG(url) FROM galleries g WHERE g.product_id = p.id) AS gallery,
    (SELECT JSON_ARRAYAGG(
        JSON_OBJECT(
            'name', a.name,
            'items', (SELECT JSON_ARRAYAGG(
                JSON_OBJECT(
                    'displayValue', ai.display_value,
                    'value', ai.value,
                    'id', ai.id
                )
            ) FROM attribute_items ai WHERE ai.attribute_id = a.id)
        )
    ) FROM attributes a WHERE a.product_id = p.id) AS attributes,
    (SELECT JSON_ARRAYAGG(
        JSON_OBJECT(
            'amount', pr.amount,
            'currency', JSON_OBJECT('label', c.label, 'symbol', c.symbol)
        )
    ) FROM prices pr LEFT JOIN currencies c ON pr.currency_label = c.label WHERE pr.product_id = p.id) AS prices
FROM 
    products p;
          ";
        return static::queryAllProducts($query);
    }
}
