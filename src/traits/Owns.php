<?php

namespace App\Models\Fire\Traits;

use Illuminate\Support\Collection;

trait Owns
{
  /**
   * Own the model
   * @method own
   * @param  \Illuminate\Database\Eloquent\Model  $model
   * @return bool
   */
  public function own(\Illuminate\Database\Eloquent\Model $model)
  {
    $ownerModel = config('owner.ownermodel');
    $checkOwner = $ownerModel::where('owner_id', $this->id)->where('owns_id', $model->id)->get();

    if(count($checkOwner) === 0) // Check if relationship already exists
    {
      $newModel = new $ownerModel;
      $newModel->owner_id = $this->id;
      $newModel->owner_model = get_class($this);
      $newModel->owns_id = $model->id;
      $newModel->owns_model = get_class($model);
      $newModel->save();
      return true;
    }
    return true;
  }
  /**
   * Disown the model
   * @method disown
   * @param  \Illuminate\Database\Eloquent\Model  $model
   * @return bool
   */
  public function disown(\Illuminate\Database\Eloquent\Model $model)
  {
    $ownerModel = config('owner.ownermodel');
    $deleteRelationship = $ownerModel::where('owns_id', $model->id)->where('owner_id', $this->id);
    $deleteRelationship->delete();
    return true;
  }

  /**
   * Query which models the user owns
   * @method owns
   * @return \Illuminate\Database\Eloquent\Model
   */
  public function owns()
  {
    $ownerModel = config('owner.ownermodel');
    return $this->returnOwnsModels($ownerModel::where('owner_id', $this->id)->get());
  }

  /**
   * Does the user own the model?
   * @method ownsModel
   * @param  \Illuminate\Database\Eloquent\Model  $model
   * @return bool
   */
  public function ownsModel(\Illuminate\Database\Eloquent\Model $model)
  {
    $ownerModel = config('owner.ownermodel');
    return (boolean) $ownerModel::where('owner_id', $this->id)->where('owns_id', $model->id)->first();
  }

  /**
   * Query which models the user owns of this type
   * @method ownsModelType
   * @param  \Illuminate\Database\Eloquent\Model  $modelType
   * @return \Illuminate\Database\Eloquent\Model
   */

  public function ownsModelType($modelType)
  {
    $ownerModel = config('owner.ownermodel');
    if(gettype($modelType) === 'object')
    {
      $modelType = get_class($modelType);
    }
    return $this->returnOwnsModels($ownerModel::where('owner_id', $this->id)->where('owns_model', $modelType)->get());
  }
  /**
   * Query the owned models
   * @method returnOwnsModels
   * @param  Collection   $ownerModels
   * @return Collection
   */
  private function returnOwnsModels(Collection $ownerModels)
  {
    $outputCollection = new Collection;
    foreach($ownerModels as $model)
    {
      $ownsModel = $model->owns_model;
      $outputModel = $ownsModel::find($model->owns_id);
      $outputCollection->push($outputModel);
    }
    return $outputCollection;
  }
}