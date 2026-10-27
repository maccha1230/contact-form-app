<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContactResource extends JsonResource
{
    /**
     * お問い合わせをJSON形式に変換する
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'last_name' => $this->last_name,
            'first_name' => $this->first_name,
            'email' => $this->email,
            'gender' => $this->gender,
            'postcode' => $this->postcode,
            'address' => $this->address,
            'building' => $this->building,
            'category' => $this->whenLoaded(
                'category',
                function () {
                    $category = $this->getRelation('category');

                    return $category ? [
                        'id' => $category->id,
                        'content' => $category->content,
                    ] : null;
                }
            ),

            'category_id' => $this->category_id,
            'detail' => $this->detail,
            'tags' => $this->whenLoaded('tags'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
