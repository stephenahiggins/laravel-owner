<?php

namespace Inventive\LaravelOwner\Traits;

trait HasOwner
{
  /**
   * Return a collection of all the model's owners
   * @method owners
   * @return \Illuminate\Database\Eloquent\Model
   */
  public function owners()
  {
    $ownerModel = config('owner.ownermodel');
    return $ownerModel::where('owns_id', $this->id)->distinct()->get(['owner_id', 'owner_model']);
  }

  /**
   * Check if model is owned by another model
   * @method isOwnedBy
   * @param  \Illuminate\Database\Eloquent\Model  $model
   * @return boolean
   */
  public function isOwnedBy($model)
  {
    $ownerModel = config('owner.ownermodel');
    return (boolean) $ownerModel::where('owner_id', $model->id)->where('owns_id', $this->id)->first();
  }

  /**
   * Add an owner to a model
   * @method isOwnedBy
   * @param  \Illuminate\Database\Eloquent\Model  $model
   * @return boolean
   */

  public function addOwner($model)
  {
    $ownerModel = config('owner.ownermodel');
    $checkOwner = $ownerModel::where('owner_id', $this->id)->where('owns_id', $model->id)->get();

    if(count($checkOwner) === 0) // Check if relationship already exists
    {
      $newModel = new $ownerModel;
      $newModel->owner_id = $model->id;
      $newModel->owner_model = get_class($model);
      $newModel->owns_id = $this->id;
      $newModel->owns_model = get_class($this);
      $newModel->save();
      return true;
    }
    return true;
  }

  /**
   * Remove an owner from a model
   * @method isOwnedBy
   * @param  \Illuminate\Database\Eloquent\Model  $model
   * @return boolean
   */
  public function removeOwner($model)
  {
    $ownerModel = config('owner.ownermodel');

    $deleteRelationship = $ownerModel::where('owns_id', $this->id)->where('owner_id', $model->id);
    $deleteRelationship->delete();

    return true;
  }
}
