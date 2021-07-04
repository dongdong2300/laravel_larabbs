<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TopicResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $data = parent::toArray($request);

        $data['user_id']     = (int)$data['user_id'];
        $data['category_id'] = (int)$data['category_id'];

        $data['user']        = new UserResource($this->whenLoaded('user'));
        $data['category']    = new CategoryResource($this->whenLoaded('category'));
        $data['top_replies'] = ReplyResource::collection($this->whenLoaded('topReplies'));

        return $data;
    }
}
