<template>
    <!-- content -->
    <q-card class="my-card" style="width: 1400px; max-width: 80vw">
      <q-card-section class="bg-primary text-white row">
        <div class="text-h6">{{ title + ' ' + solici_id }}</div>
        <q-space />
        <q-btn icon="close" color="negative" round  v-close-popup />
      </q-card-section>
      <q-form @submit.prevent="submit">
        <div class="row">
          <q-card-section class="col-12 col-md-6 row q-pa-md">
            <div class="col-12 q-py-sm text-weight-bold text-subtitle2">SOLICITANTE</div>
            <div class="col-12 col-sm-3 items-center row q-py-sm text-weight-bold ">Nombres:</div>
            <div class="col-12 col-sm-9 items-center row q-py-sm">{{solicitud?.solicitante.nombre_completo}}</div>
            <div class="col-12 q-py-sm text-weight-bold text-subtitle2">DATOS DEL DOCUMENTO</div>
            <div class="col-12 col-sm-3 items-center row q-py-sm text-weight-bold ">Escritura Pública:</div>
            <div class="col-12 col-sm-9 items-center row q-py-sm">{{solicitud?.sub_serie.nombre}}</div>
            <div class="col-12 col-sm-3 items-center row q-py-sm text-weight-bold ">Notario:</div>
            <div class="col-12 col-sm-9 items-center row q-py-sm">{{solicitud?.notario.nombre_completo}}</div>
            <div class="col-12 col-sm-3 items-center row q-py-sm text-weight-bold ">Otorgantes:</div>
            <div class="col-12 col-sm-9 items-center row q-py-sm">{{solicitud?.otorgantes}}</div>
            <div class="col-12 col-sm-3 items-center row q-py-sm text-weight-bold ">Favorecidos:</div>
            <div class="col-12 col-sm-9 items-center row q-py-sm">{{solicitud?.favorecidos}}</div>
            <div class="col-12 col-sm-3 items-center row q-py-sm text-weight-bold ">Lugar y Fecha:</div>
            <div class="col-12 col-sm-9 items-center row q-py-sm">{{solicitud?.ubigeo.nombre}}, {{ solicitud?.fecha }}</div>
            <div class="col-12 col-sm-3 items-center row q-py-sm text-weight-bold ">Bien:</div>
            <div class="col-12 col-sm-9 items-center row q-py-sm">{{solicitud?.bien}}</div>
            <div class="col-12 col-sm-3 items-center row q-py-sm text-weight-bold ">Otros Datos:</div>
            <div class="col-12 col-sm-9 items-center row q-py-sm">{{solicitud?.mas_datos}}</div>
          </q-card-section>
          <q-card-section class="col-12 col-md-6 q-pa-md">
            <div class="q-gutter-md q-mb-md">
              <div class="row">
              <q-input class="col-12 col-md-6 q-pa-sm"
              dense
              outlined
              v-model="form.cod_protocolo"
              :loading="form.validating"
              label="Protocolo *"
              @change="form.validate('cod_protocolo')"
              :error="form.invalid('cod_protocolo')"
              :class="form.invalid('cod_protocolo') ? 'q-mb-sm' : ''"
              ><template v-slot:prepend>
                <q-icon name="mdi-key" />
              </template>
              <template v-slot:error>
                <div>
                  {{ form.errors.cod_protocolo }}
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
        </div>
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

  const emits = defineEmits(["save"]);
  const props = defineProps({
    title: String,
    solici_id: {type:Number,default:null},
    solicitud: {default: null,},
  });
  let form = useForm("post", "api/registro_busquedas", {
      solicitud_id: props.solici_id,
      cod_protocolo: "",
      cod_escritura: "",
      cod_folioInicial: "",
      cod_folioFinal: "",
      observaciones: "",
    });
  onMounted(() => {
    console.log(props.solici_id);
  });
  const submit = () => {
    form
      .submit()
      .then((response) => {
        form.reset();
        // form.setData()
        console.log(response);
        emits("save",'busqueda');
      })
      .catch((error) => {
        // alert(error);
      });
  };
  defineExpose({
    form,
  });
  </script>
  <style scoped>
  .my-card{
    width: 100%;
    max-width: 80vw;
  }
  </style>
  