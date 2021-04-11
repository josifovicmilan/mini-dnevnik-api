<?php

namespace App\Observers;

use App\Models\Position;

class PositionObserver
{
    /**
     * Handle the Position "created" event.
     *
     * @param  \App\Models\Position  $position
     * @return void
     */
    public function created(Position $position)
    {
        //
    }

    /**
     * Handle the Position "updated" event.
     *
     * @param  \App\Models\Position  $position
     * @return void
     */
    public function updated(Position $position)
    {
        // dd($position->getOriginal('position')); //1
        // dd($position->position); //1

        if($position->getOriginal('position') > $position->position){
            $order = 'desc';
            $positionRange = [$position->position, $position->getOriginal('position')];
        }
        $positionRange = [$position->getOriginal('position') , $position->position ];
        $order = 'asc';
    

        $positionsBetween = Position::where('positionable_type', $position->positionable_type)
                                    ->whereBetween('position', $positionRange)
                                    ->where('id','!=' ,$position->id)
                                    ->get();

        $positionsBetween->each(function($item) use ($order){
            if($order === 'desc'){
                $item->position++;
            }else{
                $item->position--;
            }
            $item->saveQuietly();
        });
    }

    /**
     * Handle the Position "deleted" event.
     *
     * @param  \App\Models\Position  $position
     * @return void
     */
    public function deleted(Position $position)
    {
   
        //dd($position->position);
        $greaterPositions = Position::where('position', '>' ,$position->position)
                                    ->where('positionable_type', $position->positionable_type)
                                    ->get();

        $greaterPositions->each(function($item){
            $item->position--;
            $item->saveQuietly();
        });                     
    }

    /**
     * Handle the Position "restored" event.
     *
     * @param  \App\Models\Position  $position
     * @return void
     */
    public function restored(Position $position)
    {
        //
    }

    /**
     * Handle the Position "force deleted" event.
     *
     * @param  \App\Models\Position  $position
     * @return void
     */
    public function forceDeleted(Position $position)
    {
        
    }
}
