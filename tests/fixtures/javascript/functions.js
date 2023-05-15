function noOpFunction() {}

function withArguments(arg1, arg2) {}

function withArgAndBody(arg1) {
    const t = 1;

    return t + arg1;
}

function withObjectAsArg(obj) {
    const t = obj.SOME_CONSTANT;
    const a = obj.someFunction();

    a.hello = 'world';
    a.render('arg1', 'arg2');

    return obj;
}

withObjectAsArg({});
