<?php

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Modules\Client\Entities\Client;
use Modules\Common\Helpers\NotificationType;
use Modules\Common\Helpers\ProductType;
use Modules\Contracts\Entities\Contract;
use Modules\Designs\Entities\Design;
use Modules\Projects\Entities\Project;
use Modules\Quotes\Entities\Quote;
use Modules\Roles\Entities\Role;
use Modules\Users\Entities\User;

/**
 * Send a notification to frontend.
 *
 * @param NotificationType $type
 * @param string $msg
 * @return void
 */
function notification(NotificationType $type, string $msg): void
{
    Session::flash('notification', [
        'id' => Str::random(),
        'type' => $type->toString(),
        'msg' => $msg
    ]);
}

/**
 * Indicate if a sync operation has changes.
 *
 * @param array $result
 * @return bool
 */
function hasSyncChanges(array $result): bool
{
    return count($result['attached']) || count($result['updated']) || count($result['detached']);
}

/**
 * Sanitize contact array from null values.
 *
 * @param array|null $contacts
 * @return array
 */
function cleanContactArray(?array $contacts): array
{
    if (! $contacts) {

        $contacts = [];
    }

    foreach ($contacts as $index => $contact) {
        if (!isset($contact['phone']) && !isset($contact['name'])) {
            unset($contacts[$index]);
        }
    }

    return $contacts;
}

/**
 * Return Product Type array
 *
 * @param array $types
 * @return \Illuminate\Support\Collection
 */
function pluckProductTypes(array $types): \Illuminate\Support\Collection
{
    $collection = collect();
    foreach (array_column($types, 'name') as $index => $type) {

        $lowerCased = strtolower($type);

        $obj = new stdClass();
        $obj->id = $lowerCased;
        $obj->name = __('fields.' . $lowerCased);

        $collection->push($obj);
    }

    return $collection;
}

/**
 * @param float $value
 * @return string
 */
function money(float $value): string
{
    return '$ ' . number_format($value, 2);
}

/**
 * @param float $value
 * @return string
 */
function percentage(float $value): string
{
    return number_format($value, 2) . ' %';
}

function is_json($value): bool
{
    if (is_numeric($value)) {

        return false;
    }

    try {

        return json_decode($value) !== null;

    } catch (Throwable $e) {

        return false;
    }
}

function invoicesTemplates(): \Illuminate\Support\Collection
{
    $templates = array(
        'spark' => 'Spark',
        'nofiscal' => 'Factura no Fiscal',
        'imprenta' => 'Factura para imprenta',
        'detalles' => 'Detalle de venta',
        'facturanueva' => 'Factura de venta nueva',
        'factura' => 'Factura de venta'

    );

    $collection = collect();

    foreach ($templates as $key => $template) {
        $obj = new stdClass();
        $obj->id = $key;
        $obj->name = $template;

        $collection->push($obj);
    }

    return $collection;
}
function getProductsIds($products): array
{
    $collection = collect($products);

    $products_filters_arr_id = [];
    foreach ($collection as $c) {
        array_push($products_filters_arr_id, $c['id']);
    }

    return $products_filters_arr_id;
}

function getMonthlyRented($contract, $products)
{
    $monthly_rented = 0;

    foreach ($contract->products as $product) {


        if (in_array($product->id, $products)) {
            $monthly_rented =  $monthly_rented + $product->pivot->subtotal;
        }
    }

    return $monthly_rented;
}

function getMonthlyTotalRented($contracts, $products)
{
    /**
     * todo: Verificar que contract->project existe en el filtro de proyectos
     */

    $total = 0;

    foreach ($contracts as $contract) {
        $total = $total + getMonthlyRented($contract, $products);
    }

    return $total;
}

function inferStringRepresentationForModel($id_string, $model_id){

    $stringValue = $model_id;


    switch ($id_string) {
        case 'client_id':
            $stringValue = Client::where('id', $model_id)->first()?->name ?? "";
            break;
        case 'project_id':
            $stringValue = Project::where('id', $model_id)->first()?->name ?? "";
            break;
        case 'user_id':
            $stringValue = User::where('id', $model_id)->first()?->name ?? "";
            break;
        case 'contract_id':
            $stringValue =   Contract::where('id', $model_id)->first()?->name ?? "";
            break;
        case 'role_id':
            $stringValue =  Role::where('id', $model_id)->first()?->name ?? "";
            break;
    }

    return $stringValue;

}
