<template>
  <q-select
    label="Apellidos y nombres de favorecidos"
    :model-value="favorecido"
    @update:model-value="(e) => emits('update:favorecido', e)"
    use-input
    :options="options"
    option-label="nombre_completo"
    option-value="id"
    dense
    @filter="filterFn"

  >
  </q-select>
</template>
<script setup>
import { onMounted, ref } from "vue";
import FavorecidoService from "src/services/FavorecidoService";

const props = defineProps(["favorecido"]);
const emits = defineEmits(["update:favorecido"]);

const options = ref(null);
const stringOptions = ref(null);

onMounted(async () => {
  stringOptions.value = (
    await FavorecidoService.getData({
      params: {
        sort_by: "id",
        direction: "desc",
      },
    })
  ).data;
});

function filterFn(val, update, abort) {
  // call abort() at any time if you can't retrieve data somehow

  update(
    async () => {
      if (val === "") {
        options.value = stringOptions.value;
      } else {
        const needle = val.toLowerCase();
        options.value = (
          await FavorecidoService.getData({
            params: { sort_by: "id", direction: "desc", search: needle },
          })
        ).data;
      }
    },

    // "ref" is the Vue reference to the QSelect
    (ref) => {
      if (val !== "" && ref.options.length > 0 && ref.getOptionIndex() === -1) {
        ref.moveOptionSelection(1, true); // focus the first selectable option and do not update the input-value
        ref.toggleOption(ref.options[ref.optionIndex], true); // toggle the focused option
      }
    }
  );
}
</script>
