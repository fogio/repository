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
