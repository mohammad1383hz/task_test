<?php

namespace App\Http\Resources\Task;


use Illuminate\Http\Resources\Json\JsonResource;

class  TaskResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'done_at' => $this->done_at ? true : false,
            



        ];
    }
}
