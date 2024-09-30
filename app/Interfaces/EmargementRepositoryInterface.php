<?php

namespace App\Interfaces;

use Carbon\Carbon;

interface EmargementRepositoryInterface
{
    public function createForGroup(array $apprenantIds, $date);
    public function createOrUpdateForApprenant($apprenantId, $date);
    public function getForPromotion($promotionId, array $filters = []);
    public function updateForApprenant($apprenantId, $date, array $data);
    public function markAbsentees(Carbon $date);
}