#!/usr/bin/env node

import { readFile, readFileSync, writeFile } from "fs";
import { basename, resolve } from "path";
import { createInterface } from "readline";

import { parse } from "@babel/parser";
import mkdirp from "mkdirp";
import yargs from "yargs";

const argv = yargs(process.argv.splice(2)).argv;
const outputDir = resolve(argv.output ?? "./ast/");

function parseAndWrite(filePath) {
    if (!filePath) {
        return;
    }

    readFile(filePath, "utf8", (_, content) => {
        const ast = parse(content);
        const filename = basename(filePath, '.js');
        const output = `${outputDir}/${filename}.json`;

        writeFile(output, JSON.stringify(ast, null, "  "), (err) => {
            if (!err) {
                return;
            }

            console.error(`Failed to write "${output}": ${err}`);
        });
    });
}

mkdirp.sync(outputDir);

if (argv.inventory) {
    const inventoryPath = resolve(argv.inventory);
    const files = readFileSync(inventoryPath).toString().split("\n");

    files.forEach(parseAndWrite);
} else {
    const io = createInterface({
        input: process.stdin,
        output: process.stdout,
        terminal: false,
    });

    io.on("line", parseAndWrite);
}
