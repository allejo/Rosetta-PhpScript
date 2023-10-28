<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

$ignoreList = ['TemplateElement'];

function patches(string $className, string $property, string $value)
{
    $patches = [
        'TemplateElement' => [
            'value' => 'array{cooked: null|string, raw: string}',
        ],
    ];

    return $patches[$className][$property] ?? $value;
}

function sanitizeClassName($name): string
{
    $reservedClassNames = ['Class', 'Function'];

    if (in_array($name, $reservedClassNames, true)) {
        return $name . '_';
    }

    return $name;
}

$fileTemplate = <<<'TEMPLATE'
<?php

namespace allejo\Rosetta\Babel;

class {ClassName} {ClassSuffix} {
{ClassBody}
}
TEMPLATE;

$propTemplate = <<<'PROP'
/** @var {type} */
public ${name};
PROP;

$spec = file_get_contents('https://raw.githubusercontent.com/babel/babel/main/packages/babel-parser/ast/spec.md');
$re = '/^(interface .+{[\s\S][^}]+})/m';
$interfaces = [];
preg_match_all($re, $spec, $interfaces, PREG_SET_ORDER, 0);

foreach ($interfaces as $matches) {
    $interface = explode("\n", $matches[1]);
    $header = $interface[0];
    $body = array_slice($interface, 1, count($interface) - 2);

    $headerRe = '/interface (?P<ClassName>\w+)(?: <\: (?P<Extends>[\s\w,]+))? {/m';
    $headerParts = [];
    preg_match_all($headerRe, $header, $headerParts, PREG_SET_ORDER, 0);

    $bodyRe = '/^\s+(?P<PropertyName>\w+)(?P<Nullable>\?)?:? (?P<DataType>["\w\W][^;]+);?(?:.+)?$/m';
    $bodyParts = [];

    foreach ($body as $line) {
        $bodyPartsLine = [];
        preg_match_all($bodyRe, $line, $bodyPartsLine, PREG_SET_ORDER, 0);
        $bodyParts[] = $bodyPartsLine;
    }

    $rawClassName = $headerParts[0]['ClassName'];
    $className = sanitizeClassName($rawClassName);

    $extendsStr = $headerParts[0]['Extends'] ?? '';
    $extendsStr = str_replace(' ', '', $extendsStr);
    $extends = $extendsStr ? explode(',', $extendsStr) : [];

    if (count($extends) > 1) {
        printf("{$className} has multiple extends\n");
    }

    $classBody = '';
    $outputFile = sprintf('%s/../src/Babel/%s.php', __DIR__, $className);

    if (in_array($className, $ignoreList, true)) {
        printf("Skipping body for {$className}... PLEASE DO MANUALLY\n");

        if (file_exists($outputFile)) {
            continue;
        }
    } else {
        $classBody = implode("\n\n", array_map(static function ($parts) use ($className, $propTemplate) {
            $propName = $parts[0]['PropertyName'];
            $dataType = $parts[0]['DataType'];
            $dataType = patches($className, $propName, $dataType);

            if ($dataType[0] === '"' && $dataType[-1] === '"' && substr_count($dataType, '"') === 2) {
                return sprintf('public $%s = %s;', $propName, $dataType);
            }

            return strtr($propTemplate, [
                '{type}' => $dataType,
                '{name}' => $propName,
            ]);
        }, $bodyParts));
    }

    $output = strtr($fileTemplate, [
        '{ClassName}' => $className,
        '{ClassSuffix}' => count($extends) > 0 ? sprintf('extends %s', sanitizeClassName($extends[0])) : '',
        '{ClassBody}' => $classBody,
    ]);

    file_put_contents($outputFile, $output);
}

$placeholderClasses = [
    ['Declaration', 'Statement'],
    ['Expression', 'Node'],
    ['Literal', 'Expression'],
    ['ModuleDeclaration', 'Node'],
    ['Pattern', 'Node'],
    ['Statement', 'Node'],
];

foreach ($placeholderClasses as $placeholderClass) {
    $output = strtr($fileTemplate, [
        '{ClassName}' => $placeholderClass[0],
        '{ClassSuffix}' => 'extends ' . $placeholderClass[1],
        '{ClassBody}' => '',
    ]);

    file_put_contents(sprintf('%s/../src/Babel/%s.php', __DIR__, $placeholderClass[0]), $output);
}

printf("\n\nThis script only gets you like 98%% there with the conversions!\n");
printf("PLEASE FIX THINGS MANUALLY\n");
