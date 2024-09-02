<div>
    <x-input-label for="node_version" :value="__('Node Version')" />
    <x-text-input
        value="{{ old('node_version') }}"
        id="node_version"
        name="node_version"
        type="text"
        class="mt-1 block w-full"
        placeholder=""
    />
    @error("node_version")
        <x-input-error class="mt-2" :messages="$message" />
    @enderror
</div>
