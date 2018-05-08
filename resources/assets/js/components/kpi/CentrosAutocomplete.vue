<template>
    <div class="form-group">
        <div>
            <select class="form-control" v-model="kind">
                <option selected value="0">Selecciona una opción</option>
                <option value="1">Centros de consumo</option>
                <option value="2">Zonas</option>
            </select>
        </div>
        <div v-if="kind != 0">
            <div v-if="kind == 1">
                <autocomplete
                        name="centro"
                        :source="centros"
                        results-display="CENTRO"
                        results-value="ID_CENTRO"
                        placeholder="Centro"
                        input-class="form-control"
                        class="form-control"
                >
                </autocomplete>
            </div>
            <div v-else>
                <autocomplete
                        name="zona"
                        :source="zonas"
                        results-display="MUNICIPIO"
                        results-value="ID_MUNICIPIO"
                        placeholder="Zona"
                        input-class="form-control"
                        class="form-control"
                >
                </autocomplete>
            </div>
        </div>
    </div>
</template>

<script>
    import Autocomplete from 'vuejs-auto-complete'

    export default {
        name: "centros-autocomplete",
        components: {Autocomplete},
        props: ['user', 'centros', 'zonas'],
        data() {
            return {
                kind: 0
            }
        }
    }
</script>

<style scoped>

</style>