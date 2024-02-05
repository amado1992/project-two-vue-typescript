<?php

namespace Modules\Companies\Application;

use Illuminate\Support\Str;
use Modules\Brands\Entities\Brand;
use Modules\Brands\Http\Requests\UpdateBrandRequest;
use Modules\Companies\Entities\Company;
use Modules\Companies\Events\CompanyUpdated;
use Modules\Companies\Events\UpdatingCompany;
use Modules\Companies\Http\Requests\UpdateCompanyRequest;

/**
 * Update a brand.
 *
 * @author cheynerpb.
 */
class UpdateCompanyUseCase
{
    /**
     * @param UpdateCompanyRequest $request
     * @param Company $company
     * @return bool
     */
    public function __invoke(UpdateCompanyRequest $request, Company $company = null): bool
    {
        $userID = auth()->user()->getAuthIdentifier();
        $updatedBy = $userID;

        if(!isset($company)) {
            $company = new Company();
            $company->created_by = $userID;
            $updatedBy = null;
        }

        $contacts = cleanContactArray($request->input('contacts') ?? []);

        $company->forceFill([
            'name' => $request->input('name'),
            'social_reason' => $request->input('social_reason'),
            'ruc' => $request->input('ruc'),
            'dv' => $request->input('dv'),
            'contact_information' => json_encode($contacts),
            'email' => Str::lower($request->input('email')),
            'color' => $request->input('color'),
            'address' => $request->input('address')
        ]);

        if ($company->isDirty()) {

            $company->fill([
                'updated_by' => $updatedBy
            ]);

            UpdatingCompany::dispatch($company);
        }

        $company->save();

        $logoChanged = false;
        if($request->hasFile('logo')){
            $company->saveImage($request->file('logo'));
            $logoChanged = true;
        }

        $wasChanged = $company->wasChanged() || $logoChanged;

        if ($wasChanged) {

            CompanyUpdated::dispatch($company);
        }

        return $wasChanged;
    }
}
