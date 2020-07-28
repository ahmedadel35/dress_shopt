<template>
    <content-loader height="350" primary-color="#cbcbcd" secondary-color="#02103b">
        <rect x="-1" y="1" rx="0" ry="0" width="104" height="16" />
        <rect x="116" y="2" rx="0" ry="0" width="98" height="15" />
        <rect x="306" y="1" rx="0" ry="0" width="117" height="16" />
        <rect x="6" y="42" rx="0" ry="0" width="379" height="78" />
        <rect x="9" y="148" rx="0" ry="0" width="73" height="14" />
        <rect x="111" y="149" rx="0" ry="0" width="34" height="14" />
        <rect x="155" y="149" rx="0" ry="0" width="34" height="14" />
        <rect x="198" y="149" rx="0" ry="0" width="34" height="14" />
        <rect x="241" y="150" rx="0" ry="0" width="34" height="12" />
        <rect x="9" y="188" rx="0" ry="0" width="73" height="14" />
        <rect x="111" y="188" rx="0" ry="0" width="34" height="14" />
        <rect x="154" y="188" rx="0" ry="0" width="34" height="14" />
        <rect x="197" y="188" rx="0" ry="0" width="34" height="14" />
        <rect x="240" y="187" rx="0" ry="0" width="34" height="15" />
        <rect x="106" y="232" rx="0" ry="0" width="176" height="28" />
        <rect x="11" y="239" rx="0" ry="0" width="73" height="14" />
        <rect x="11" y="297" rx="0" ry="0" width="95" height="16" />
        <rect x="188" y="295" rx="0" ry="0" width="95" height="16" />
    </content-loader>
</template>
<style lang="scss"></style>
<script lang="ts">
import { Vue, Component, Prop, Watch } from "vue-property-decorator";

@Component
export default class QuickViewLoader extends Vue {}
</script>
