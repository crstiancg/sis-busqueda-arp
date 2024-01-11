<template>
    <!-- content -->
    <q-card class="my-card" style="width: 1400px; max-width: 80vw">
      <q-card-section class="bg-primary text-white">
        <div class="text-h6">{{ title }}</div>
        <!-- <div class="text-subtitle2">Usuario</div> -->
      </q-card-section>
      <q-form @submit.prevent="submit">
        <q-card-section class="q-pa-md">
          <div class="q-gutter-md q-mb-md">
            <!-- {{ busquedaForm }} -->
            <div class="row">
             <q-input class="col-12 col-md-6 q-pa-sm"
             dense
             outlined
             v-model="form.protocolo"
             :loading="form.validating"
             label="Protocolo *"
             @change="form.validate('protocolo')"
             :error="form.invalid('protocolo')"
             :class="form.invalid('protocolo') ? 'q-mb-sm' : ''"
             ><template v-slot:prepend>
               <q-icon name="mdi-key" />
             </template>
             <template v-slot:error>
               <div>
                 {{ form.errors.protocolo }}
               </div>
             </template>
           </q-input>
             <q-input class="col-12 col-md-6 q-pa-sm"
             dense
             outlined
             v-model="form.cod_escritura"
             :loading="form.validating"
             label="Codigo de Escritura *"
             @change="form.validate('cod_escritura')"
             :error="form.invalid('cod_escritura')"
             :class="form.invalid('cod_escritura') ? 'q-mb-sm' : ''"
             ><template v-slot:prepend>
               <q-icon name="mdi-key" />
             </template>
             <template v-slot:error>
               <div>
                 {{ form.errors.cod_escritura }}
               </div>
             </template>
           </q-input>
             <q-input class="col-12 col-md-6 q-pa-sm"
             dense
             outlined
             v-model="form.cod_folioInicial"
             :loading="form.validating"
             label="Codigo Folio Inicial *"
             @change="form.validate('cod_folioInicial')"
             :error="form.invalid('cod_folioInicial')"
             :class="form.invalid('cod_folioInicial') ? 'q-mb-sm' : ''"
             ><template v-slot:prepend>
               <q-icon name="mdi-key" />
             </template>
             <template v-slot:error>
               <div>
                 {{ form.errors.cod_folioInicial }}
               </div>
             </template>
           </q-input>
             <q-input class="col-12 col-md-6 q-pa-sm"
             dense
             outlined
             v-model="form.cod_folioFinal"
             :loading="form.validating"
             label="Codigo Folio Final *"
             @change="form.validate('cod_folioFinal')"
             :error="form.invalid('cod_folioFinal')"
             :class="form.invalid('cod_folioFinal') ? 'q-mb-sm' : ''"
             ><template v-slot:prepend>
               <q-icon name="mdi-key" />
             </template>
             <template v-slot:error>
               <div>
                 {{ form.errors.cod_folioFinal }}
               </div>
             </template>
           </q-input>
             <q-input class="col-12 col-md-12 q-pa-sm"
             dense
             type="textarea"
             outlined
             v-model="form.observaciones"
             :loading="form.validating"
             label="observaciones *"
             @change="form.validate('observaciones')"
             :error="form.invalid('observaciones')"
             :class="form.invalid('observaciones') ? 'q-mb-sm' : ''"
             ><template v-slot:prepend>
               <q-icon name="mdi-key" />
             </template>
             <template v-slot:error>
               <div>
                 {{ form.errors.observaciones }}
               </div>
             </template>
           </q-input>
            </div>
          </div>
        </q-card-section>
        <q-separator />
  
        <q-card-actions align="right">
          <q-btn label="Cancelar" flat v-close-popup></q-btn>
          <q-btn
            label="Guardar"
            :loading="form.processing"
            type="submit"
            color="positive"
          ></q-btn>
        </q-card-actions>
      </q-form>
    </q-card>
  </template>
  
  <script setup>
  import { useForm } from "laravel-precognition-vue";
  import { onMounted, ref } from "vue";
  // import RoleService from "src/services/RoleService"
  // const isPwd = ref(true);
  // const roles = ref(false);
  const emits = defineEmits(["save"]);
  const formBusqueda = ref();
  
  const props = defineProps({
    title: String,
    id: Number,
    edit: {
      type: Boolean,
      default: false,
    }
  });

  let busquedaForm = 15;
  
  let form;
  if (props.edit) {
    form = useForm("put", "api/registro_busquedas/" + props.id, {
      id: "",
      nombre: "",
      
  
    });
  } else {
    form = useForm("post", "api/registro_busquedas", {
      solicitud_id: busquedaForm,
      protocolo: "",
      cod_escritura: "",
      cod_folioInicial: "",
      cod_folioFinal: "",
      observaciones: "",
    });

    console.log(form);
  }
  // async function cargar() {
  //   const { data } = await RoleService.getData({
  //     params: { rowsPerPage: 0, order_by: "id" },
  //   });
  //   roles.value = data;
  //   console.log(roles.value);
  // }
  
  const submit = () => {
    form
      .submit()
      .then((response) => {
        form.reset();
        // form.setData()
  
        emits("save");
      })
      .catch((error) => {
        // alert("An error occurred.");
      });
  };

  function setValue(values){
    busquedaForm = values;

    console.log(busquedaForm);
  }
  
  onMounted(() => {
    // setData();
    console.log(props.edit);
    // cargar();
    // console.log(form);
  });
  
  defineExpose({
    // setData,
    setValue,
    form,
  });
  </script>
  <style scoped>
  .my-card{
    width: 100%;
    max-width: 80vw;
  }
  </style>
  