const aSimpleString = 'hello world';
const someStringConcatenation = 'hello' + ' ' + 'bob';
const aTemplateStringWithNoVariables = `lorem ipsum`;
const aTemplateStringWithVariables = `here's an ${someStringConcatenation}${aSimpleString} embedded string: ${aSimpleString} in the middle`;

const aNumber = 123456;
const aDecimal = 123.45;
const aFalseBool = false;
const aTrueBool = true;

const anObject = { hello: 'world', [aSimpleString]: 'variable key' };
const anArray = [1, 2, 3, 4, '5', '6', '7', true, false, { hello: 'world' }];
const aCallback = (arg1, arg2) => { return arg1 + arg2 };
