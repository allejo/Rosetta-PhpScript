<?php
/*
 * Language: Bash
 * Author: vah <vahtenberg@gmail.com>
 * Contributrors: Benjamin Pannell <contact@sierrasoftworks.com>
 * Website: https://www.gnu.org/software/bash/
 * Category: common
 */

use allejo\Rosetta\test\Mock\RegEx;

/** @type LanguageFn */
function bash($hljs)
{
    $regex = $hljs->regex;
    $VAR = (object)[];
    $BRACED_VAR = (object)[
        'begin' => new RegEx('\$\{'),
        'end' => new RegEx('\}'),
        'contains' => [
            'self',
            (object)[
                'begin' => new RegEx(':-'),
                'contains' => [$VAR]
            ]
        ]
    ];
    $VAR['className'] = 'variable';
    $VAR['variants'] = [
        (object)[
            'begin' => $regex->concat(new RegEx('\$[\w\d#@][\w\d_]*'), '(?![\w\d])(?![$])'),
        ],
        $BRACED_VAR,
    ];
    $SUBST = (object)[
        'className' => 'subst',
        'begin' => new RegEx('\$\('),
        'end' => new RegEx('\)'),
        'contains' => [$hljs->BACKSLASH_ESCAPE],
    ];
    $HERE_DOC = (object)[
        'begin' => new RegEx('<<-?\s*(?=\w+)'),
        'starts' => (object)[
            'contains' => [
                $hljs->END_SAME_AS_BEGIN([
                    'begin' => new RegEx('(\w+)'),
                    'end' => new RegEx('(\w+)'),
                    'className' => 'string'
                ]),
            ],
        ],
    ];
    $QUOTE_STRING = (object)[
        'className' => 'string',
        'begin' => new RegEx('"'),
        'end' => new RegEx('"'),
        'contains' => [
            $hljs->BACKSLASH_ESCAPE,
            $VAR,
            $SUBST,
        ],
    ];
    $SUBST->contains[] = $QUOTE_STRING;
    $ESCAPED_QUOTE = (object)[
        'match' => new RegEx('\\"'),
    ];
    $APOS_STRING = (object)[
        'className' => 'string',
        'begin' => new RegEx("'"),
        'end' => new RegEx("'"),
    ];
    $ESCAPED_APOS = (object)[
        'match' => new RegEx("\\'"),
    ];
    $ARITHMETIC = (object)[
        'begin' => new RegEx('\$?\(\('),
        'end' => new RegEx('\)\)'),
        'contains' => [
            (object)[
                'begin' => new RegEx('\d+#[0-9a-f]+'),
                'className' => 'number',
            ],
            $hljs->NUMBER_MODE,
            $VAR,
        ],
    ];
    $SH_LIKE_SHELLS = [
        "fish",
        "bash",
        "zsh",
        "sh",
        "csh",
        "ksh",
        "tcsh",
        "dash",
        "scsh",
    ];
    $KNOWN_SHEBANG = $hljs->SHEBANG((object)[
        'binary' => sprintf("(%s)", implode('|', $SH_LIKE_SHELLS)),
        'relevance' => 10,
    ]);
    $FUNCTION = (object)[
        'className' => 'function',
        'begin' => new RegEx('\w[\w\d_]*\s*\(\s*\)\s*\{'),
        'returnBegin' => true,
        'contains' => [
            $hljs->inherit(
                $hljs->TITLE_MODE,
                (object)[
                    'begin' => new RegEx('\w[\w\d_]*'),
                ]
            )
        ],
        'relevance' => 0,
    ];
    $KEYWORDS = [
        "if",
        "then",
        "else",
        "elif",
        "fi",
        "for",
        "while",
        "until",
        "in",
        "do",
        "done",
        "case",
        "esac",
        "function",
        "select"
    ];
    $LITERALS = [
        "true",
        "false"
    ];
    $PATH_MODE = (object)[
        'match' => new RegEx('(\/[a-z._-]+)+'),
    ];
    $SHELL_BUILT_INS = [
        "break",
        "cd",
        "continue",
        "eval",
        "exec",
        "exit",
        "export",
        "getopts",
        "hash",
        "pwd",
        "readonly",
        "return",
        "shift",
        "test",
        "times",
        "trap",
        "umask",
        "unset"
    ];
    $BASH_BUILT_INS = [
        "alias",
        "bind",
        "builtin",
        "caller",
        "command",
        "declare",
        "echo",
        "enable",
        "help",
        "let",
        "local",
        "logout",
        "mapfile",
        "printf",
        "read",
        "readarray",
        "source",
        "type",
        "typeset",
        "ulimit",
        "unalias"
    ];
    $ZSH_BUILT_INS = [
        "autoload",
        "bg",
        "bindkey",
        "bye",
        "cap",
        "chdir",
        "clone",
        "comparguments",
        "compcall",
        "compctl",
        "compdescribe",
        "compfiles",
        "compgroups",
        "compquote",
        "comptags",
        "comptry",
        "compvalues",
        "dirs",
        "disable",
        "disown",
        "echotc",
        "echoti",
        "emulate",
        "fc",
        "fg",
        "float",
        "functions",
        "getcap",
        "getln",
        "history",
        "integer",
        "jobs",
        "kill",
        "limit",
        "log",
        "noglob",
        "popd",
        "print",
        "pushd",
        "pushln",
        "rehash",
        "sched",
        "setcap",
        "setopt",
        "stat",
        "suspend",
        "ttyctl",
        "unfunction",
        "unhash",
        "unlimit",
        "unsetopt",
        "vared",
        "wait",
        "whence",
        "where",
        "which",
        "zcompile",
        "zformat",
        "zftp",
        "zle",
        "zmodload",
        "zparseopts",
        "zprof",
        "zpty",
        "zregexparse",
        "zsocket",
        "zstyle",
        "ztcp"
    ];
    $GNU_CORE_UTILS = [
        "chcon",
        "chgrp",
        "chown",
        "chmod",
        "cp",
        "dd",
        "df",
        "dir",
        "dircolors",
        "ln",
        "ls",
        "mkdir",
        "mkfifo",
        "mknod",
        "mktemp",
        "mv",
        "realpath",
        "rm",
        "rmdir",
        "shred",
        "sync",
        "touch",
        "truncate",
        "vdir",
        "b2sum",
        "base32",
        "base64",
        "cat",
        "cksum",
        "comm",
        "csplit",
        "cut",
        "expand",
        "fmt",
        "fold",
        "head",
        "join",
        "md5sum",
        "nl",
        "numfmt",
        "od",
        "paste",
        "ptx",
        "pr",
        "sha1sum",
        "sha224sum",
        "sha256sum",
        "sha384sum",
        "sha512sum",
        "shuf",
        "sort",
        "split",
        "sum",
        "tac",
        "tail",
        "tr",
        "tsort",
        "unexpand",
        "uniq",
        "wc",
        "arch",
        "basename",
        "chroot",
        "date",
        "dirname",
        "du",
        "echo",
        "env",
        "expr",
        "factor",
        "groups",
        "hostid",
        "id",
        "link",
        "logname",
        "nice",
        "nohup",
        "nproc",
        "pathchk",
        "pinky",
        "printenv",
        "printf",
        "pwd",
        "readlink",
        "runcon",
        "seq",
        "sleep",
        "stat",
        "stdbuf",
        "stty",
        "tee",
        "test",
        "timeout",
        "tty",
        "uname",
        "unlink",
        "uptime",
        "users",
        "who",
        "whoami",
        "yes"
    ];

    return (object)[
        'name' => 'Bash',
        'aliases' => ['sh'],
        'keywords' => (object)[
            '$pattern' => new RegEx('\b[a-z][a-z0-9._-]+\b'),
            'keyword' => $KEYWORDS,
            'literal' => $LITERALS,
            'built_in' => array_merge(
                $SHELL_BUILT_INS,
                $BASH_BUILT_INS,
                "set",
                "shopt",
                $ZSH_BUILT_INS,
                $GNU_CORE_UTILS
            ),
        ],
        'contains' => [
            $KNOWN_SHEBANG,
            $hljs->SHEBANG(),
            $FUNCTION,
            $ARITHMETIC,
            $hljs->HASH_COMMENT_MODE,
            $HERE_DOC,
            $PATH_MODE,
            $QUOTE_STRING,
            $ESCAPED_QUOTE,
            $APOS_STRING,
            $ESCAPED_APOS,
            $VAR,
        ],
    ];
}
