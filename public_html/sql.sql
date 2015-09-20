select * from 
    offers as O INNER JOIN goods as G INNER JOIN goods_t_:GT as GT
where
    O.goods_id = G.id and G.id = GT.goods_id -- связь 3-х таблиц
    and G.goods_type_type = :GT -- Тип товара
     -- Поставщик, если есть
    and O.providers_id = :providers_id
    -- Параметры товара, если есть
    and GT.brand = :brend