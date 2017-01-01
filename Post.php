<?php

class PostEntity extends Repository
{

    protected function provideActivities()
    {
        return [
            
            // cache
            (new Fogio\Repository\Activity\Cache()),

            // operations
            (new Fogio\Repository\Entity\Operation())->setActivities(

            ),

            // transaction
            (new Fogio\Repository\Activity\DbTransaction()),

            // subentities and other stuff
            (new Fogio\Repository\Entity\Sub())->setTable(fogio()->db->location),
            (new Fogio\Repository\Entity\OriginBan()),
            (new Fogio\Repository\Entity\Rel()),
            (new Fogio\Repository\Entity\Link()),

            // entity
            (new Fogio\Repository\Entity\Base()),
            (new Fogio\Repository\Entity\Main())->setTable(fogio()->db->post),
            
        ];
        
    }

}

class PostTable extends Repository
{

    protected function provideActivities()
    {
        return [

            // cache
            (new Fogio\Repository\Activity\Cache()),

            // table operations
            (new Fogio\Repository\Entity\Operation())->setActivities(

            ),

            // table
            (new Fogio\Repository\Table\Table())->setTable(fogio()->db->post),

        ];

    }

}


class Feed extends Repository
{

    protected function provideActivities()
    {
        return [

            // cache
            (new Fogio\Repository\Activity\Cache()),

            // table
            (new Fogio\Repository\Feed\Feed())->setUri('http://www.wykop.pl/rss/'),

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
