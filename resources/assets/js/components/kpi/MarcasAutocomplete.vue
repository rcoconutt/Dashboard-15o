<template>
    <div class="form-group">
        <autocomplete
                name="marca"
                id="marca"
                :source="marcas"
                results-display="MARCA"
                results-value="ID_MARCA"
                placeholder="Marca"
                input-class="form-control"
                class="form-control"
        >
        </autocomplete>
    </div>
</template>

<script>
    import Autocomplete from 'vuejs-auto-complete'

    export default {
        name: "marcas-autocomplete",
        components: {Autocomplete},
        props: ['user', 'marcas']
    }
</script>

<style scoped>

</style>