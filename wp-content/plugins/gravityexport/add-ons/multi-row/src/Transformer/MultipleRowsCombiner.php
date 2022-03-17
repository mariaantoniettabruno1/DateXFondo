<?php

namespace GravityKit\GravityExport\MultiRow\Transformer;

use GFExcel\Transformer\Combiner;

/**
 * A combiner that returns multiple rows for multi-row fields.
 *
 * @since 1.0
 */
class MultipleRowsCombiner extends Combiner {
	/**
	 * @inheritdoc
	 * @since 1.0
	 */
	public function parseEntry( array $fields, array $entry ): void {
		// Always start at zero.
		$column_index = 0;
		$rows         = [];

		foreach ( $fields as $field ) {
			// Iterate every row a field returns.
			$i = 0;
			foreach ( $this->getFieldRows( $field, $entry ) as $row ) {
				// Initialize row if it doesn't exist yet.
				if ( ! isset( $rows[ $i ] ) ) {
					$rows[ $i ] = [];
				}

				$rows = $this->fillMissingColumns( $rows, $i, $column_index );
				// Merge values with the current row.
				$rows[ $i ] = array_merge( $rows[ $i ], $row );
				$i++;
			}

			// Keep track of the current column count.
			$column_index += count( $field->getColumns() );
		}

		// Now that we have all rows and data, fill out the remaining missing cells.
		foreach ( $rows as $i => $row ) {
			$rows = $this->fillMissingColumns( $rows, $i, $column_index );
		}

		$this->rows = array_merge( $this->rows, $rows );
	}

	/**
	 * Fills out any missing cells up to this point with `null`.
	 *
	 * @since 1.0
	 *
	 * @param mixed[] $rows  The rows so far.
	 * @param int     $row   The row ID to fill out.
	 * @param int     $total The total count of fields for the row.
	 *
	 * @return array The full rows.
	 */
	private function fillMissingColumns( array $rows, int $row, int $total ): array {
		$length = count( $rows[ $row ] );
		if ( $length < $total ) {
			for ( $x = $length; $x < $total; $x++ ) {
				$rows[ $row ][] = null;
			}
		}

		return $rows;
	}
}
