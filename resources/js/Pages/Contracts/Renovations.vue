<template>
    <Head :title="$t('contracts.renovations.title')" />

    <AuthenticatedLayout>
        <template #header>
            {{ $t("contracts.renovations.title") }}
        </template>

        <div class="container">
            <div class="tw-flex tw-justify-end">
                <PrimaryButton v-if="computedmakesNewRenovation && statusContract == statusDefeated"
                    icon="add"
                    @click="createRenovation"
                    v-show="contract.crud_permissions.add_renovations"
                >
                    {{ $t("contracts.renovations.create") }}
                </PrimaryButton>
            </div>
            <q-card
                class="tw-p-6 tw-mt-5"
                v-for="renovation in contract.renovations"
            >
                <q-card-section>
                    <div class="tw-flex tw-justify-between">
                        <div>
                            <strong class="tw-block">{{
                                $t("fields.id")
                            }}</strong>
                            {{ renovation.id }}
                        </div>
                        <div>
                            <strong class="tw-block">{{
                                $t("fields.from")
                            }}</strong>
                            {{ renovation.start }}
                        </div>
                        <div>
                            <strong class="tw-block">{{
                                $t("fields.to")
                            }}</strong>
                            {{ renovation.finish }}
                        </div>
                        <div>
                            <strong class="tw-block">{{
                                $t("fields.renovated_by")
                            }}</strong>
                            {{ renovation.commercial.name }}
                        </div>
                        <div>
                            <strong class="tw-block">{{
                                $t("fields.date")
                            }}</strong>
                            {{ renovation.created_at }}
                        </div>
                    </div>
                    <q-markup-table class="tw-mt-3 tw-shadow-none">
                        <thead>
                            <tr>
                                <th class="text-right">
                                    {{ $t("fields.id") }}
                                </th>
                                <th class="text-right">
                                    {{ $t("fields.name") }}
                                </th>
                                <th class="text-right">
                                    {{ $t("fields.price") }}
                                </th>
                                <th class="text-right">
                                    {{ $t("fields.mesu_rented_delivered") }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="product in renovation.products">
                                <td class="text-right">{{ product.id }}</td>
                                <td class="text-right">
                                    <ProductName :name="product.name" />
                                </td>
                                <td class="text-right">
                                    {{ money(product.pivot.price) }}
                                </td>
                                <td class="text-right">
                                    {{
                                        product.pivot.mesu_delivered +
                                        "/" +
                                        product.pivot.re_rent_delivered
                                    }}
                                </td>
                            </tr>
                        </tbody>
                    </q-markup-table>

                    <div class="tw-flex tw-justify-end tw-justify-items-center">
                        <div
                            class="tw-flex tw-flex-col"
                        >
                           <div class="tw-m-2 text-right">
                            <strong class="tw-text-lg tw-text-right">{{
                                $t("fields.subtotal") + ": "
                            }}</strong>
                            <span class="tw-text-lg">{{
                                money(renovation.subtotal)
                            }}</span>
                           </div>

                           <div class="tw-m-2 text-right">
                            <strong class="tw-text-lg tw-text-right">{{
                                $t("fields.tax") + ": "
                            }}</strong>
                            <span class="tw-text-lg">{{
                                money(renovation.tax)
                            }}</span>
                           </div>

                            <div class="tw-m-2 text-right">
                                <strong
                                class="tw-text-lg tw-text-right text-primary"
                                >{{ $t("fields.total") + ": " }}</strong
                            >
                            <span class="tw-text-lg text-primary">{{
                                money(renovation.total)
                            }}</span>
                            </div>
                        </div>
                    </div>
                </q-card-section>
            </q-card>

            <div class="tw-flex tw-justify-end tw-mt-5">
                <SecondaryButton
                    :to="route('contracts.show', { contract: contract.id })"
                >
                    {{ $t("actions.back") }}
                </SecondaryButton>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup lang="ts">
import { Head, router } from "@inertiajs/vue3";
import AuthenticatedLayout from "../../Layouts/AuthenticatedLayout.vue";
import PrimaryButton from "../../Components/PrimaryButton.vue";
import { useQuasar } from "quasar";
import RenovationDialog from "./RenovationDialog.vue";
import { money } from "../../Common/helpers";
import SecondaryButton from "../../Components/SecondaryButton.vue";
import route from "ziggy-js";
import ProductName from "../../Components/ProductName.vue";
import { computed } from "vue";

const props = defineProps<{
    contract;
}>();

const $q = useQuasar();

var statusDefeated: string = "defeated"
var statusContract: string = "";
var makesNewRenovation: boolean = false;

const computedmakesNewRenovation = computed(() => {
    const data = props.contract;
    let sumReturn: number = 0;
    let sumQuantity: number = 0;
    statusContract = data.status;

    for (let product of data.products) {
        sumReturn += product.pivot.mesu_return + product.pivot.re_rent_return;
        sumQuantity += product.pivot.quantity;
    }

    if (sumReturn < sumQuantity) {
        makesNewRenovation = true;
    }

    return makesNewRenovation;
});

async function createRenovation() {
    axios
        .get(
            route("contracts.products", {
                contract: props.contract.id,
            })
        )
        .then((response) => {
            $q.dialog({
                component: RenovationDialog,
                componentProps: {
                    contract: response.data.contract,
                },
            }).onOk(() => {
                router.reload({
                    only: ["contract"],
                });
            });
        });
}
</script>
