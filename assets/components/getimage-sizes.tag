<getimage-sizes>

    <label class="uk-display-block uk-margin-small" if="{ currentAsset.sizes }">{ App.i18n.get('Size') }</label>

    <select data-size if="{ currentAsset.sizes }">
        <option value="" selected></option>
        <option value="{ k }" each="{ k in Object.keys(currentAsset.sizes) }">{ k }</option>
    </select>

    <script>

        this.currentAsset = {};

    </script>

</getimage-sizes>
