<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Interfaces\KeyResultInterface;
use App\Interfaces\ObjectiveInterface;
use App\Interfaces\OKRInterface;
use Illuminate\Http\Request;

class OKRController extends Controller
{
    protected $okrInterface;
    protected $objectiveInterface;
    protected $keyResultInterface;

    public function __construct(ObjectiveInterface $objectIn, KeyResultInterface $keyResultIn, OKRInterface $okrIn)
    {
        $this->objectiveInterface = $objectIn;
        $this->keyResultInterface = $keyResultIn;
        $this->okrInterface = $okrIn;
    }

    /** Show All Objective */
    public function showAllObjective()
    {
        return $this->objectiveInterface->getAllObjective();
    }

    public function showObjective($id)
    {
        return $this->objectiveInterface->findObjectiveById($id);
    }

    /** Store Objective */
    public function storeObjective(Request $req)
    {
        return $this->objectiveInterface->saveObjective($req);
    }

    /** Update Objective */
    public function updateObjective(Request $req, $id)
    {
        return $this->objectiveInterface->updateObjective($req, $id);
    }

    public function deleteObjective($id)
    {
        return $this->objectiveInterface->deleteObjective($id);
    }

    /** Show All Parent Objective */
    public function showAllParentObjective()
    {
        return $this->objectiveInterface->getAllParentObjective();
    }

    /** Set Objective to Parent Objective */
    public function assignObjectToParent(Request $req, $id)
    {
        return $this->objectiveInterface->setParentObjectiveByObjectiveId($req, $id);
    }

    /** Group Count Objective */
    public function getGroupObjective()
    {
        return $this->objectiveInterface->getCountObjective();
    }




    /** Show All Key Result By Auth User */
    public function showAllKeyResultByAuth()
    {
        return $this->keyResultInterface->getAllKeyResultByAuthUser();
    }

    /** Find Key Result By ID */
    public function showKeyResultById($id)
    {
        return $this->keyResultInterface->findKeyResultById($id);
    }

    /** Store Key Result */
    public function storeKeyResult(Request $req)
    {
        return $this->keyResultInterface->saveKeyResult($req);
    }

    /** Update Key Result */
    public function updateKeyResult(Request $req, $id)
    {
        return $this->keyResultInterface->updateKeyResult($req, $id);
    }



    /** Show All Key Results By Objective ID */
    public function showAllOKRByObjectiveId($id)
    {
        return $this->okrInterface->getAllKeyResultByObjectiveId($id);
    }


    /** Check in Key Result By User Auth */
    public function checkInOKRByAuthUser(Request $req)
    {
        return $this->okrInterface->checkInOKRByAuthUser($req);
    }


    /** Show All Key Result  User Tracking */
    public function showAllKeyResultUserTracking()
    {
        return $this->okrInterface->getAllKeyResultTrackingUser();
    }

    public function showObjectiveKeyResultProgression()
    {
        return $this->okrInterface->getObjectiveKeyResultProgression();
    }

    public function showAllHistoryKeyResultByObjective()
    {
        return $this->okrInterface->getAllHistoryKeyResultByObjective();
    }

    /** Update OKR Level */
    public function updateOkrLevel(Request $req)
    {
        return $this->okrInterface->updateOkrLevel($req);
    }

}
