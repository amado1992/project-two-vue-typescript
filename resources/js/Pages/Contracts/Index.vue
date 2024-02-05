<template>
  <Head :title="$t('contracts.title')" />

  <AuthenticatedLayout>
    <template #header>
      <q-avatar>
        <q-icon name="description" />
      </q-avatar>
      {{ $t("contracts.title") }}
    </template>

    <div class="q-pa-md">
      <q-table
        class="fixed-column-table"
        :columns="pagination.columns"
        :rows="computedPaginationData"
        :filter="filter"
        v-model:pagination="pagination"
        row-key="name"
        @request="onRequest"
      >

      <template v-slot:header="props">
            <q-tr :props="props">
              <q-th
                v-for="col in props.cols"
                :key="col.name"
                :props="props"
                style="text-align: center !important;"
              >
                {{ col.label }}
              </q-th>
            </q-tr>
      </template>

        <template #top-right>
          <q-input
            dense
            debounce="300"
            v-model="filter"
            :placeholder="$t('actions.search')"
          >
            <template v-slot:append>
              <q-icon name="search" />
            </template>
          </q-input>
          <PrimaryButton
            v-if="can('create_contracts', $page.props)"
            class="tw-ml-3"
            icon="add"
            to="contracts.create"
          />
        </template>
        <template #body-cell-id="props">
          <q-td class="text-right">
            <Link
              class="text-blue"
              :href="
                route('contracts.show', {
                  contract: props.row.id,
                })
              "
              v-if="props.row.crud_permissions.edit"
            >
              {{ props.row.id }}
            </Link>
            <span v-else>{{ props.row.id }}</span>
          </q-td>
        </template>
        <template #body-cell-date="props">
          <q-td :props="props">
            <span
              :class="{
                'text-blue hover:tw-cursor-pointer':
                  props.row.crud_permissions.update_date,
              }"
              >{{ props.row.date }}</span
            >
            <q-popup-proxy
              cover
              transition-show="scale"
              transition-hide="scale"
              v-if="props.row.crud_permissions.update_date"
              @show="date = props.row.date"
            >
              <DatePicker v-model="date" @ok="changeDate(props.row)" />
            </q-popup-proxy>
          </q-td>
        </template>
        <template #body-cell-active="props">
          <q-td class="q-table--col-auto-width text-center">
            <q-icon
              size="25px"
              name="check_circle"
              v-show="props.row.active"
              color="primary"
            />
            <q-icon
              size="25px"
              name="cancel"
              v-show="!props.row.active"
              color="negative"
            />
          </q-td>
        </template>
        <template #body-cell-status="props">
          <q-td class="text-right">
            <span
              class="tw-p-1 tw-rounded tw-text-white"
              :class="status(props.row.status, i18n)[1]"
            >
              {{ status(props.row.status, i18n)[0] }}
            </span>
          </q-td>
        </template>
        <template #body-cell-actions="props">
          <RowActions :actions="getActions(props.row)" />
        </template>
      </q-table>
    </div>
  </AuthenticatedLayout>
</template>

<script setup lang="ts">
import { Head, Link, useForm } from "@inertiajs/vue3";
import { useI18n } from "vue-i18n";
import { router, usePage } from "@inertiajs/vue3";
import { Paginator } from "../../Models/Paginator";
import { ref, computed } from "vue";
import { RowAction } from "../../Models/RowAction";
import AuthenticatedLayout from "../../Layouts/AuthenticatedLayout.vue";
import PrimaryButton from "../../Components/PrimaryButton.vue";
import RowActions from "../../Components/RowActions.vue";
import { can, getUpdatedAt, money, status } from "../../Common/helpers";
import route from "ziggy-js";
import { useQuasar } from "quasar";
import StartContractDialog from "./StartContractDialog.vue";
import DatePicker from "../../Components/DatePicker.vue";
import { Contract } from "../../Models/Contract";
import { usePagination } from "../../Composables/pagination";

const props = defineProps<{
  contracts;
}>();
const i18n = useI18n();
const $q = useQuasar();

const { pagination, filter, onRequest } = usePagination(props.contracts, [
  {
    name: "id",
    required: true,
    sortable: true,
    filterable: true,
    label: i18n.t("fields.id"),
    field: "id",
  },
  {
    name: "project",
    required: true,
    align: "left",
    sortable: true,
    filterable: true,
    label: i18n.t("fields.project"),
    field: (row) => row.project?.name ?? "",
    classes: "product-col",
  },
  {
    name: "project",
    required: true,
    align: "left",
    sortable: false,
    filterable: false,
    label: i18n.t("fields.client"),
    field: (row) => row.project?.client_name ?? row.project?.client?.name ?? "",
    classes: "product-col",
    data: "project.client.name",
  },
  {
    name: "commercial",
    required: true,
    align: "left",
    sortable: true,
    filterable: true,
    label: i18n.t("fields.commercial"),
    field: (row) => row.commercial?.name ?? "",
    data: "user.name",
  },
  {
    name: "total",
    required: true,
    sortable: false,
    filterable: false,
    label: i18n.t("fields.total"),
    field: (row) => money(row.total),
    data: "total",
  },
  {
    name: "date",
    required: true,
    align: "left",
    sortable: true,
    filterable: true,
    label: i18n.t("fields.start_date"),
    field: "date",
  },

  {
    name: "renovation_date",
    required: true,
    align: "left",
    sortable: false,
    filterable: false,
    label: i18n.t("fields.renovation_date"),
    field: "expire_at",
  },
  {
    name: "remainder_days",
    required: true,
    sortable: false,
    filterable: false,
    label: i18n.t("fields.remainder_days"),
    field: (row) => getRemainderDays(row.expire_at),
    data: "remainder_days",
  },
  {
    name: "status",
    required: false,
    align: "center",
    sortable: false,
    filterable: false,
    label: i18n.t("fields.status"),
  },
  {
    name: "created_at",
    required: true,
    align: "left",
    sortable: true,
    filterable: true,
    label: i18n.t("fields.created_at"),
    field: "created_at",
  },
  {
    name: "created_by",
    required: false,
    align: "left",
    sortable: false,
    filterable: false,
    label: i18n.t("fields.created_by"),
    field: (row) => row.created_by.name,
  },
  {
    name: "updated_at",
    required: false,
    align: "left",
    sortable: true,
    filterable: true,
    label: i18n.t("fields.updated_at"),
    field: (row) => getUpdatedAt(row),
    data: "updated_at",
  },
  {
    name: "updated_by",
    required: false,
    align: "left",
    sortable: false,
    filterable: false,
    label: i18n.t("fields.updated_by"),
    field: (row) => row.updated_by?.name,
  },
  {
    name: "actions",
    label: i18n.t("actions.title"),
  },
]);

const date = ref(null);
const computedPaginationData = computed(() => {
    const data = pagination.value.data as Array<Object>;
    const ordenStatus = ["defeated", "active", "renovated", "pending", "finished", "cancelled", "annulled"];

    const compareStatus = (a: any, b: any): number => {
        const indexA = ordenStatus.indexOf(a.status);
        const indexB = ordenStatus.indexOf(b.status);
        return indexA - indexB;
    };

    const compareCreatedAt = (a: any, b: any): number => {
        const DateA = new Date(a.created_at);
        const DateB = new Date(b.created_at);
        return DateB.getTime() - DateA.getTime();
    };

    const result = data.sort((a: any, b: any) => {
        const statusComparison = compareStatus(a, b);
        if (statusComparison != 0) {
            return statusComparison;
        } else {
            return compareCreatedAt(a, b);
        }
    });

    return result;
});

/**
 *
 * @param {{id, name, phone, active, created_at}} row
 * @return {RowAction[]}
 */
function getActions(row: any): RowAction[] {
  let sumCarriedByClient: number = 0;
  let sumReturn: number = 0;
  let sumQuantity: number = 0;
  const statusDefeated: string = "defeated"
  let status: string = "";
  status = row.status;

  for (let product of row.products) {
    sumReturn += product.pivot.mesu_return + product.pivot.re_rent_return;
    sumQuantity += product.pivot.quantity;
    sumCarriedByClient += product.pivot.carried_by_client;
  }

  const actions = [
    new RowAction().apply((action) => {
      action.label = i18n.t("actions.details");
      action.icon = "info";
      action.route = "contracts.show";
      action.args = { contract: row.id };
    }),
    new RowAction().apply((action) => {
      action.label = i18n.t("actions.download");
      action.icon = "download";
      action.callback = () => {
        window.location.href = route("contracts.download", {
          contract: row.id,
        });
        return true;
      };
    }),
    new RowAction().apply((action) => {
      action.label = i18n.t("invoices.title");
      action.icon = "receipt";
      action.route = "invoices.index";
      action.args = { contract: row.id };
    }),
  ];

  if (row.crud_permissions.returns) {
    actions.push(
      new RowAction().apply((action) => {
        action.label = i18n.t("contracts.travels.title");
        action.icon = "local_shipping";
        action.route = "contracts.travels";
        action.args = { contract: row.id };
      })
    );
  }

  if (row.crud_permissions.start && sumQuantity == sumCarriedByClient) {
    actions.push(
      new RowAction().apply((action) => {
        action.label = i18n.t("actions.start");
        action.icon = "play_arrow";
        action.onSuccess = () => { };
        action.callback = () => {
          $q.dialog({
            component: StartContractDialog,
            componentProps: {
              contract: row,
            },
          }).onOk(() => {
            reloadContracts();
          });

          return true;
        };
      })
    );
  }

  if (row.crud_permissions.finish) {
    actions.push(
      new RowAction().apply((action) => {
        action.label = i18n.t("actions.finish");
        action.icon = "done";
        action.route = "contracts.finish";
        action.args = { contract: row.id };
        action.onSuccess = () => {
          reloadContracts();
        };
      })
    );
  }

  if (row.crud_permissions.returns) {
    actions.push(
      new RowAction().apply((action) => {
        action.label = i18n.t("contracts.returns.title");
        action.icon = "home_work";
        action.route = "contracts.returns";
        action.args = { contract: row.id };
      })
    );
  }

  if (row.crud_permissions.edit) {
    actions.push(
      new RowAction().apply((action) => {
        action.label = i18n.t("actions.edit");
        action.args = { contract: row.id };
        action.route = "contracts.edit";
        action.icon = "edit";
      })
    );
  }

  if (row.crud_permissions.cancel) {
    actions.push(
      new RowAction().apply((action) => {
        action.label = i18n.t("actions.annular");
        action.icon = "cancel";
        action.callback = () => {
          $q.dialog({
            title: i18n.t("messages.annular_confirmation"),
            message: i18n.t("messages.annular_confirmation_msg"),
            cancel: true,
            persistent: true,
            prompt: {
              label: i18n.t("fields.reason"),
              model: "",
            },
          }).onOk((payload) => {
            const url = route("contracts.cancel", {
              contract: row.id,
            });

            router.put(
              url,
              {
                reason: payload,
              },
              {
                onSuccess: () => {
                  reloadContracts();
                },
              }
            );
          });

          return true;
        };
      })
    );
  }

  if (row.crud_permissions.delete) {
    actions.push(
      new RowAction().apply((action) => {
        action.label = i18n.t("actions.delete");
        action.args = { contract: row.id };
        action.route = "contracts.destroy";
        action.icon = "delete";
        action.method = "delete";
        action.onSuccess = () => {
          reloadContracts();
        };
      })
    );
  }

  if (row.crud_permissions.renovations && sumReturn < sumQuantity && status == statusDefeated) {
    actions.push(
      new RowAction().apply((action) => {
        action.label = i18n.t("actions.renovation");
        action.args = { contract: row.id };
        action.route = "contracts.renovations";
        action.icon = "update";
      })
    );
  }

  return actions;
}

function reloadContracts() {
  router.reload({
    only: ["contracts"],
    preserveScroll: true,
    onSuccess: (params) => {
      pagination.value.data = params.props.contracts.pagination.data;
    },
  });
}

function getRemainderDays(daterenv: any) {
  const currentDate = new Date();
  const targetDate = new Date(daterenv);
  const differenceInTime = targetDate.getTime() - currentDate.getTime();
  const differenceInDays = Math.ceil(differenceInTime / (1000 * 3600 * 24));

  return differenceInDays >= 0 ? differenceInDays : 0;
}

function changeDate(contract: Contract) {
  const form = useForm({
    date: date.value,
  });

  form.put(route("contracts.update-date", { contract: contract.id }), {
    preserveScroll: true,
    onSuccess: () => {
      reloadContracts();
      date.value = null;
    },
  });
}

function compareExpireDescending(a: any, b: any): number {
  if (a.expire_at < b.expire_at) {
    return -1;
  } else if (a.expire_at > b.expire_at) {
    return 1;
  } else {
    return 0;
  }
}

function searchAndSortByStatus(
  data: Object[],
  status: string
): Object | undefined {
  const objetosFiltrados = data.find((value: any) => value.status == status);
  return objetosFiltrados;
}
</script>
