# Clico

Command Line Interface Coloured Output

Clico is a PHP package that gives you an expressive API to format text for CLI
output. Clico gives chainable methods to set text background and foreground
colours, weight, and other text decorations like underlining and blinking.

Clico also provides functionality for formatting tabular data for CLI output.
The table class can also format the text in each row, each column, and each
individual cell.

Clico is framework agnostic, just install and `use` the provided classes.

## Full API Docs

Full API documentation can be found [here](http://code.webb.tj/clico/docs/),
however the below guide should be enough to get most users started.

## Installation

Install via composer.

```(bash)
composer require webbtj/clico
```

## Useage

Once installed you can use the `Text` and `Table` classes in your own code.

```(bash)
use Webbtj\Clico\Text;
use Webbtj\Clico\Table;
```

Both the `Text` and `Table` classes return strings via their `__toString`
methods.

## Using `Text`

The `Text` class provides several chainable methods to alter the rendering of
text in the CLI output.

### Colours

- `black()` -  set the text or background colour to black.
- `blue()` -  set the text or background colour to blue.
- `cyan()` -  set the text or background colour to cyan.
- `green()` -  set the text or background colour to green.
- `magenta()` -  set the text or background colour to magenta.
- `red()` -  set the text or background colour to red.
- `white()` -  set the text or background colour to white.
- `yellow()` -  set the text or background colour to yellow.

### Effects

- `b()`, `bold()`, `heavyWeight()`, `strong()` - make the text bold.
- `blink()`, `flash()` - make the text blink.
- `dim()`, `lightWeight()`, `thin()` - make the text light weight (dimmer).
- `hidden()`, `hide()` - make the text invisible.
- `highlight()` - highlight the text (reverse background and foreground
colours).
- `normal()`, `normalWeight()` - make the text normal weight.
- `u()`, `underline()` - underline the text.

### Background/Foreground

By default the foreground (text) colour is being manipulated, however you can
switch between changing the background and foreground colours with chain
methods. Switching to either layer causes future chained colour methods to
affect that layer.

- `background()` - switch to manipulating the background colour.
- `foreground()` - switch to manipulating the foreground colour.

### Shading

- `dark()` - makes subsequent colour methods use their dark variants.
- `darken()` - changes the currently set colour (back- or foreground) to its
dark variant.
- `light()` - makes subsequent colour methods use their light variants.
- `lighten()` - changes the currently set colour (back- or foreground) to its
light variant.

### Utilities

- `getText()` - returns the plain (undecorated) text.
- `length()` - returns the length of the undecorated string.
- `repeat(int $repeat)` - set the text to repeat `$repeat` times at render.
`repeat()` does not affect the outputs of `getText()` or `length()`.
- `setDefault(bool $setAll)` - sets the currently set layer (back- or
foreground) to its default colour. `$setAll = true` to change both layers.
- `text(string $text)` - sets the text value. Multiple calls will override
previously set values. `$text` can also be passed to the constructor.

### Examples

```(php)
use Webbtj\Clico\Text;

echo (new Text())->text('Example #1')->red()->bold();
  // will output "Example #1" in bold, red text.

echo (new Text('Example #2))->blue()->blink();
  //will output "Example #2" in blinking, blue text.

$text = new Text();
$text->test('Example #3');
$text->foreground()->dark()->blue()->background()->light()->red();
$text->underline();
echo $text;
  // will output "Example #3" in underlined, dark blue text
  // with a light red background
```

## Using `Table`

The `Table` class provides an expressive API for formatting tabular data for CLI
output. The class provides methods for adding header and body content,
manipulating the characters used for the table layout, and provides all of the
`Text` methods which can target the whole table, a row, a column, or a single
cell.

### Adding Data

- `__construct(Array $data, bool $firstRowAsHeader)` - the constructor can 
optionally set the table data and can sepcify if the first row is the header.
- `addHeader(Array $row)` - adds the provided data as the header row. Unsets the
previous header row.
- `addRow(Row $row)` - add a single Row to the end of the table.
- `populate(Array $data)` - adds new rows for each entry in the provided array.
- `setFirstRowAsHeader()` - make the first row the header row.

### Setting Table Characters

- `setHeaderLineCharacter(string $char)` - sets the character used to separate
the header row from the table body.
- `setPipeCharacter(string $char)` - sets the character used to separate table
columns.
- `setRowLineCharacter(string $char)` - sets the character used to separate
tabke body rows.

### Formatting

The `Table` class provides all of the text formatting methods of the `Text`
class. Those methods are, by default, applied to all cells in the table. You
can specify an entire row, column, or single cell to target with subsequent
text formatting methods.

- `cell(int $column, int $row)` - set the specific cell to target for
formatting. `$column` and `$row` are zero based.
- `column(int $index)` - set the column to target for formatting. `$index` is
zero based. Omit `$index` to unset a previously selected column.
- `distributeColumns(int $width)` - sets the desired width (in characters) of
the table. This happens automatically by default with a width of `160`. If you
want a different width, this should be called **after** all data has been
populated and before rendering.
- `row(int $index)` - set the row to target for formatting. `$index` is
zero based. Omit `$index` to unset a previously selected row.

### Meta Data

- `getHeaderLineCharacter()` - gets the character used to separate
the header row from the table body. This returns the decorated text version.
- `getHeight()` - gets the number of rows in the table.
- `getPipeCharacter()` - gets the character used to separate table
columns. This returns the decorated text version.
- `getRowLineCharacter()` - gets the character used to separate
tabke body rows. This returns the decorated text version.
- `getWidth()` - gets the number of columns in the table.

## Contributing

Contributions are always welcome [on GitHub](https://github.com/webbtj/clico).
Please open issues before submitting PRs and do tag the issue in your commit
messages/PR description. Also, please adhere to PSR-2 as much as possible.
