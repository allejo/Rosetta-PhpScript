# Rosetta PhpScript

Oh dear god, why does this even exist?!

The highlight.php project is a PHP port of the highlight.js project allowing for server-side syntax highlighting. The highlight.php project is used widely in the PHP community ranging from a WordPress plugin to Symfony related websites to Laravel plugins. As highlight.js evolved, so did their grammar definitions allowing for intricate callbacks to be used. This made keeping highlight.php near impossible unless I wanted to maintain 190+ languages manually. Enter this project: parse JS grammar definitions and rewrite them as PHP grammar definitions.

Does this mean you can use this tool to convert JavaScript code into production-ready PHP code? Please no. Never do this! The amount of security holes that could be introduced because of that... The purpose of this tool is solely to translate pretty basic highlight.js grammars. So then why did I make this open source? Because it may help someone else with their niche use case.

## How does it work?

It uses Babel's parser to read JavaScript code and convert it into an abstract syntax tree (AST). Then we convert that AST into an AST that nikic's PHP-Parser supports and writes out the PHP.

## License

MIT
