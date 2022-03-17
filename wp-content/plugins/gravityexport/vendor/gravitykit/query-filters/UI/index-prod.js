import QueryFilters from './QueryFilters.svelte';

jQuery( document ).ready( function ( $ ) {
    const {
        fields,
        conditions,
        targetElementSelector,
        autoscrollElementSelector,
        inputElementName,
        translations,
    } = window.gkQueryFilters;

    if ( !$( targetElementSelector ).length || !fields.length || !inputElementName ) {
        return;
    }

    new QueryFilters( {
        target: $( targetElementSelector )[ 0 ],
        props: {
            fields,
            conditions,
            targetElementSelector,
            autoscrollElementSelector,
            inputElementName,
            translations,
        },
    } );
} );
