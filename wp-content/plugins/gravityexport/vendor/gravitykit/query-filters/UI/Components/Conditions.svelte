<script>
    import { getContext } from 'svelte';
    import { slide } from 'svelte/transition';
    import Field from './Field.svelte';
    import { set, get } from 'lodash-es';
    import shortid from 'shortid';

    export let conditionsData;
    export let conditionPath = 'conditions';

    const maxNestingLevel = 0;
    const { conditionsStore, fieldsStore, translations } = getContext( 'app' );

    /**
     * Get new field object that's used when adding conditions or fields
     *
     * @return {Object} New field object
     */
    function getNewField () {
        return {
            _id: shortid(),
            key: get( $fieldsStore, '0.key' ),
            operator: get( $fieldsStore, $fieldsStore.filters ? '0.filters.0.operators.0' : '0.operators' ),
            value: '',
        };
    };

    /**
     * Add new field to condition group
     */
    function addField () {
        conditionsStore.update( ( conditions ) => {
            const fields = get( conditions, conditionPath );
            fields.push( getNewField() );

            return conditions;
        } );
    }

    /**
     * Remove field from condition group; continue removal up the tree until a parent condition has at least one field
     *
     * @param {string} path Condition pathField index inside condition group
     * @param {number} fieldIndex Field index inside condition group
     */
    function removeField ( path, fieldIndex ) {
        conditionsStore.update( ( conditions ) => {

            let conditionGroup = get( conditions, conditionPath ).filter( ( field, index ) => index !== fieldIndex );

            if ( conditionGroup.length ) {
                return set( conditions, conditionPath, conditionGroup );
            }

            // If there are no more fields lefts inside the condition group, we need to remove
            // the group and check one level up until either we encounter a field or there are no more groups left
            const regex = /(.*?)?\.?(\d+)\.?conditions?$/;
            let updatedConditions = conditions;
            let [ , newPath, newIndex ] = regex.exec( conditionPath ) || [];

            while ( true ) {
                conditionGroup = get( updatedConditions, newPath ).filter( ( condition, index ) => index !== parseInt( newIndex, 10 ) );

                if ( conditionGroup.length ) {
                    updatedConditions = set( conditions, newPath, conditionGroup );
                    break;
                } else if ( newPath === 'conditions' ) {
                    updatedConditions = null;
                    break;
                }

                [ , newPath, newIndex ] = regex.exec( newPath );
            }

            return updatedConditions;
        } );
    };

    /**
     * Update condition field
     *
     * @param {Object} fieldData Field data
     * @param {string} fieldData.conditionFieldPath Condition path
     * @param {string} fieldData.key Field key
     * @param {string} fieldData.operator Field operator
     * @param {string} fieldData.value Field value
     */
    function updateField ( { conditionFieldPath, key, operator, value } ) {
        conditionsStore.update( ( conditions ) => {
            const field = Object.assign( {}, get( conditions, conditionFieldPath ), {
                key,
                operator,
                value,
            } );

            return set( conditions, conditionFieldPath, field );
        } );
    };

    /**
     * Add new condition group
     *
     * @param {string} path Condition path
     */
    function addConditionGroup ( path ) {
        conditionsStore.update( ( conditions ) => {
            // Root condition
            if ( !path ) {
                return {
                    _id: shortid(),
                    version: 2,
                    mode: 'and',
                    conditions: [
                        {
                            _id: shortid(),
                            mode: 'or',
                            conditions: [
                                getNewField(),
                            ],
                        },
                    ],
                };
            }

            let conditionGroup = get( conditions, path );

            conditionGroup.push( {
                _id: shortid(),
                mode: 'or',
                conditions: [
                    getNewField(),
                ],
            } );

            return set( conditions, path, conditionGroup );
        } );
    };

    /**
     * Get path to the next level of nested conditions using information from the recursive loop
     *
     * @param {string} path Current condition path
     * @param {number} index Index of the loop element
     *
     * @return {string}
     */
    function getNextLevelConditionPath ( path, index ) {
        return `${ path }.${ index }.conditions`;
    }

    /**
     * Check if maximum nesting level has been reached
     *
     * @param {string} path Condition path
     * @param {number} index Index of the loop element
     *
     * @return {boolean}
     */
    function isMaxNestingLevel ( path, index ) {
        return ( getNextLevelConditionPath( path, index ).match( /conditions/g ) || [] ).length <= maxNestingLevel;
    }

    /**
     * Determine if this is topmost condition group
     *
     * @param {string} path Condition path
     *
     * @return {boolean}
     */
    function isRootCondition ( path ) {
        return path === 'conditions';
    }
</script>

{#if !conditionsData}
    <button type="button" class='gk-query-filters-add-condition-group button button-secondary button-large' on:click={() => addConditionGroup()}>
        {translations.add_condition}
    </button>
{:else}
    {#each conditionsData as data, index(data._id)}
        {#if data.conditions}
            <div in:slide={{duration: 150}} class="gk-query-filters-condition-group" class:root={isRootCondition(conditionPath)}>
                <svelte:self conditionsData={data.conditions} conditionPath={getNextLevelConditionPath(conditionPath, index)}/>
            </div>
            {#if conditionsData.length === index + 1}
                <div class="gk-query-filters-join-condition-group gk-query-filters-group-divider" on:click={() => addConditionGroup(conditionPath)}>
                    <button class="button button-secondary button-large">+ {translations.join_and}</button>
                </div>
            {:else}
                <div class="gk-query-filters-condition-group-joined gk-query-filters-group-divider">
                    <span class="gk-query-filters-join-and">{translations.join_and}</span>
                </div>
            {/if}
        {:else}
            <Field fieldData={data} conditionFieldPath={`${conditionPath}.${index}`} onUpdate={updateField}>
                <button class="gk-query-filters-remove-field" aria-label={translations.remove_field} title={translations.remove_field} slot="remove_field" on:click={() => removeField(conditionPath, index)}>
                    <span class="dashicons-dismiss dashicons"/>
                </button>
            </Field>
            {#if conditionsData.length === index + 1}
                <button class="gk-query-filters-join-field button button-secondary" on:click={addField}>+ {translations.join_or}</button>
            {:else}
                <div class="gk-query-filters-field-joined">
                    <span class="gk-query-filters-join-or">{translations.join_or}</span>
                </div>
            {/if}
        {/if}
    {/each}
{/if}

<style type="text/scss">
  .gk-query-filters-add-condition-group {
    align-self: flex-start;
  }

  .gk-query-filters-condition-group {
    display: flex;
    flex-direction: column;

    background: #f7f7f7;
    border: .05em solid #b5bcc2;
    border-radius: 4px;
    box-shadow: 0 1px 2px #ccd0d4;
    padding: 1em;
  }

  .gk-query-filters-remove-field {
    margin-left: 1em;
    border: 0;
    background-color: inherit;
    color: #999;

    &:hover, &:focus {
      color: #C62D2D;
    }
  }

  .gk-query-filters-join-field {
    margin-top: 1em;
    align-self: flex-start;
    text-transform: uppercase;
  }

  .gk-query-filters-join-or,
  .gk-query-filters-join-and {
    font-size: 13px;
    text-transform: uppercase;
    padding: .33em .6em;
    background: #eee;
    border: #b5bcc2 1px dotted;
    color: #606a73;
    font-weight: 500;
    border-radius: 2px;
    text-align: center;
  }

  .gk-query-filters-field-joined {
    margin: .25em 0;

    .gk-query-filters-join-or {
      font-size: 12px;
      display: inline-flex;
      margin: .5em 0;
      background: #e9e9e9; // Slightly darker against the group #f7f7f7 background
      font-weight: 600;
    }
  }

  .gk-query-filters-join-condition-group {
    margin: 0 auto;

    button {
      text-transform: uppercase;
    }
  }

  .gk-query-filters-condition-group-joined {
    margin: 0 auto;

    .gk-query-filters-join-and {
      border-style: solid; // And is more "solid" than OR
      margin: 0 auto;
      display: inline-block;
      min-width: 3em;
    }
  }

  .gk-query-filters-group-divider:before,
  .gk-query-filters-group-divider:not(.gk-query-filters-join-condition-group):after {
    content: '';
    width: .1em;
    height: 1.5em;
    background: #b5bcc2;
    display: block;
    margin: 0 auto .25em;
  }

  .gk-query-filters-group-divider:not(.gk-query-filters-join-condition-group):after {
    margin: .25em auto 0;
  }

  @media screen and (max-width: 782px) {
    .gk-query-filters-remove-field {
      order: -1;
      margin: 0 0 1em 0;
    }

    .gk-query-filters-field-joined .gk-query-filters-join-or {
      margin: 1em auto !important;
    }
  }

  button {
    cursor: pointer;
  }
</style>
