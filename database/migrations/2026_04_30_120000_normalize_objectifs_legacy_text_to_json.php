<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class NormalizeObjectifsLegacyTextToJson extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('objectifs')
            ->select('id', 'actionCle', 'resultatAttendu', 'isjson')
            ->orderBy('id')
            ->chunkById(200, function ($objectifs) {
                foreach ($objectifs as $objectif) {
                    $updates = [];

                    $actionCle = trim((string)($objectif->actionCle ?? ''));
                    $resultatAttendu = trim((string)($objectif->resultatAttendu ?? ''));

                    if (!$this->isValidJsonArray($actionCle)) {
                        $updates['actionCle'] = json_encode($actionCle === '' ? [] : [$actionCle]);
                    }

                    if (!$this->isValidJsonArray($resultatAttendu)) {
                        $updates['resultatAttendu'] = json_encode($resultatAttendu === '' ? [] : [$resultatAttendu]);
                    }

                    if ((int)$objectif->isjson !== 1) {
                        $updates['isjson'] = true;
                    }

                    if (!empty($updates)) {
                        $updates['updated_at'] = now();
                        DB::table('objectifs')->where('id', $objectif->id)->update($updates);
                    }
                }
            });
    }

    private function isValidJsonArray($value)
    {
        if ($value === '') {
            return false;
        }

        json_decode($value, true);
        return json_last_error() === JSON_ERROR_NONE && is_array(json_decode($value, true));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Non réversible sans perte d'information sur le format legacy initial.
    }
}
