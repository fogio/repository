<?php

class Post extends Repository 
{

    protected function provideExtensions()
    {
        return [
            
            // cache
            (new Fogio\Repository\Extension\Cache()),


            // operations
            (new Fogio\Repository\Extensions\EntityOperation())->setExtensions(

            ),

            
            // transaction
            (new Fogio\Repository\Extension\DbTransaction()),


            // subentities and other stuff
            (new Fogio\Repository\Extension\EntitySub())->setTable(fogio()->db->location),
            (new Fogio\Repository\Extension\EntityOriginBan()),
            (new Fogio\Repository\Extension\Link()),



            // a) as entity
            (new Fogio\Repository\Extension\EntityBase()),
            (new Fogio\Repository\Extension\EntityMain())->setTable(fogio()->db->post),

            // b) or as simple table
            (new Fogio\Repository\Extension\Table())->setTable(fogio()->db->post),

            
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
