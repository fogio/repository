<?php

class Post extends Repository 
{

    protected function provideExtensions()
    {
        return [
            
            // cache
            (new Fogio\Repository\Extension\Cache()),

            // a) operations on entity
            (new Fogio\Repository\Entity\Operation())->setExtensions(

            ),

            // b) or table operatons
            (new Fogio\Repository\Entity\Operation())->setExtensions(

            ),

            // transaction
            (new Fogio\Repository\Extension\DbTransaction()),


            // subentities and other stuff
            (new Fogio\Repository\Entity\Sub())->setTable(fogio()->db->location),
            (new Fogio\Repository\Entity\OriginBan()),
            (new Fogio\Repository\Entity\Link()),

            // a) as entity
            (new Fogio\Repository\Entity\Base()),
            (new Fogio\Repository\Entity\Main())->setTable(fogio()->db->post),

            // b) or as simple table
            (new Fogio\Repository\Table\Table())->setTable(fogio()->db->post),

            
        ];
        
    }


}
/**
 * Rel
 *
 * rel: id_master, id_slave, type, data, order, order_opposite
 * rel: id_a, id_b, type, data, order, order_ba
 *
 * $entity = [
 *  'entity_id' => '1234',
 *  'rel:append' => ..
 *  'rel:prepend' => ..
 *  'rel:remove' => .. objects?
 *  'rel' => [
 *      '$type:add' => [
 *          ['entity_id' => , 'data' => []]
 *      ],
 *      '$type:remove' => [],
 *      ]
 * ]
 *
 * article->fetchAll([
 *  'rel' =>
 *      master =
 *      slave =
 *      slave_and = $categires
 *      master_and = [[123, 234], [345, 456]]
 *
 * ]);
 *
 * 
 *
 *
 */
