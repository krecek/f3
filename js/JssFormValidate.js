var JssForm = JssForm || {};
JssForm.getValue = function (elem) {
    var i;
    if (!elem) {
        return null;

    } else if (!elem.tagName) { // RadioNodeList, HTMLCollection, array
        return elem[0] ? JssForm.getValue(elem[0]) : null;

    } else if (elem.type === 'radio') {
        var elements = elem.form.elements; // prevents problem with name 'item' or 'namedItem'
        for (i = 0; i < elements.length; i++) {
            if (elements[i].name === elem.name && elements[i].checked) {
                return elements[i].value;
            }
        }
        return null;

    } else if (elem.type === 'file') {
        return elem.files || elem.value;

    } else if (elem.tagName.toLowerCase() === 'select') {
        var index = elem.selectedIndex,
            options = elem.options,
            values = [];

        if (elem.type === 'select-one') {
            return index < 0 ? null : options[index].value;
        }

        for (i = 0; i < options.length; i++) {
            if (options[i].selected) {
                values.push(options[i].value);
            }
        }
        return values;

    } else if (elem.name && elem.name.match(/\[\]$/)) { // multiple elements []
        var elements = elem.form.elements[elem.name].tagName ? [elem] : elem.form.elements[elem.name],
            values = [];

        for (i = 0; i < elements.length; i++) {
            if (elements[i].type !== 'checkbox' || elements[i].checked) {
                values.push(elements[i].value);
            }
        }
        return values;

    } else if (elem.type === 'checkbox') {
        return elem.checked;

    } else if (elem.tagName.toLowerCase() === 'textarea') {
        return elem.value.replace("\r", '');

    } else {
        return elem.value.replace("\r", '').replace(/^\s+|\s+$/g, '');
    }
};
JssForm.getEffectiveValue = function (elem) {
    var val = JssForm.getValue(elem);
    return val;
};

/**
 * Validation form element against given rules.
 */
JssForm.validateControl = function (elem, rules, onlyCheck, value) {
    console.log(elem, 'elem1');
    elem = elem.tagName ? elem : elem[0]; // RadioNodeList
    rules = rules || JssForm.parseJSON(elem.getAttribute('data-JssForm-rules'));
    value = value === undefined ? {value: JssForm.getEffectiveValue(elem)} : value;
    for (var id = 0, len = rules.length; id < len; id++) {
        var rule = rules[id],
            op = rule.op.match(/(~)?([^?]+)/),
            curElem = rule.control ? elem.form.elements.namedItem(rule.control) : elem;
        if (!curElem) {
            continue;
        }

        rule.neg = op[1];
        rule.op = op[2];
        rule.condition = !!rule.rules;
        curElem = curElem.tagName ? curElem : curElem[0]; // RadioNodeList

        var curValue = elem === curElem ? value : {value: JssForm.getEffectiveValue(curElem)},
            success = JssForm.validateRule(curElem, rule.op, rule.arg, curValue);

        if (success === null) {
            continue;
        } else if (rule.neg) {
            success = !success;
        }

        if (rule.condition && success) {
            if (!JssForm.validateControl(elem, rule.rules, onlyCheck, value)) {
                return false;
            }
        } else if (!rule.condition && !success) {
            if (JssForm.isDisabled(curElem)) {
                continue;
            }
            if (!onlyCheck) {
                var arr = JssForm.isArray(rule.arg) ? rule.arg : [rule.arg],
                    message = rule.msg.replace(/%(value|\d+)/g, function (foo, m) {
                        return JssForm.getValue(m === 'value' ? curElem : elem.form.elements.namedItem(arr[m].control));
                    });
                JssForm.addError(curElem, message);
            }
            return false;
        }
    }
    return true;
};

JssForm.isDisabled = function (elem) {
    if (elem.type === 'radio') {
        for (var i = 0, elements = elem.form.elements; i < elements.length; i++) {
            if (elements[i].name === elem.name && !elements[i].disabled) {
                return false;
            }
        }
        return true;
    }
    return elem.disabled;
};


/**
 * Display error message.
 */
JssForm.addError = function (elem, message) {
    if (message) {
        $(elem).closest("div.form-group").addClass('has-error');
        $(elem).closest("div.form-group").append("<span class='help-block'>" + message + "</span>");
    }
    if (elem.focus) {
        elem.focus();
    }
};

JssForm.expandRuleArgument = function (form, arg) {
    if (arg && arg.control) {
        arg = JssForm.getEffectiveValue(form.elements.namedItem(arg.control));
    }
    return arg;
};


/**
 * Validates single rule.
 */
JssForm.validateRule = function (elem, op, arg, value) {
    value = value === undefined ? {value: JssForm.getEffectiveValue(elem)} : value;

    if (op.charAt(0) === ':') {
        op = op.substr(1);
    }
    op = op.replace('::', '_');
    op = op.replace(/\\/g, '');
    console.log(op, 'op');
    var arr = JssForm.isArray(arg) ? arg.slice(0) : [arg];
    for (var i = 0, len = arr.length; i < len; i++) {
        arr[i] = JssForm.expandRuleArgument(elem.form, arr[i]);
    }
    return JssForm.validators[op]
        ? JssForm.validators[op](elem, JssForm.isArray(arg) ? arr : arr[0], value.value, value)
        : null;
};


JssForm.validators = {
    filled: function (elem, arg, val) {
        return val !== '' && val !== false && val !== null
            && (!JssForm.isArray(val) || !!val.length)
            && (!window.FileList || !(val instanceof FileList) || val.length);
    },
    required: function (elem, arg, val) {
        return JssForm.validators.filled(elem, arg, val);
    },

    blank: function (elem, arg, val) {
        return !JssForm.validators.filled(elem, arg, val);
    },

    valid: function (elem, arg, val) {
        return JssForm.validateControl(elem, null, true);
    },

    equal: function (elem, arg, val) {
        if (arg === undefined) {
            return null;
        }

        function toString(val) {
            if (typeof val === 'number' || typeof val === 'string') {
                return '' + val;
            } else {
                return val === true ? '1' : '';
            }
        }

        val = JssForm.isArray(val) ? val : [val];
        arg = JssForm.isArray(arg) ? arg : [arg];
        loop:
            for (var i1 = 0, len1 = val.length; i1 < len1; i1++) {
                for (var i2 = 0, len2 = arg.length; i2 < len2; i2++) {
                    if (toString(val[i1]) === toString(arg[i2])) {
                        continue loop;
                    }
                }
                return false;
            }
        return true;
    },

    notEqual: function (elem, arg, val) {
        return arg === undefined ? null : !JssForm.validators.equal(elem, arg, val);
    },

    minLength: function (elem, arg, val) {
        return val.length >= arg;
    },

    maxLength: function (elem, arg, val) {
        return val.length <= arg;
    },

    length: function (elem, arg, val) {
        arg = JssForm.isArray(arg) ? arg : [arg, arg];
        return (arg[0] === null || val.length >= arg[0]) && (arg[1] === null || val.length <= arg[1]);
    },

    email: function (elem, arg, val) {
        return (/^("([ !#-[\]-~]|\\[ -~])+"|[-a-z0-9!#$%&'*+\/=?^_`{|}~]+(\.[-a-z0-9!#$%&'*+\/=?^_`{|}~]+)*)@([0-9a-z\u00C0-\u02FF\u0370-\u1EFF]([-0-9a-z\u00C0-\u02FF\u0370-\u1EFF]{0,61}[0-9a-z\u00C0-\u02FF\u0370-\u1EFF])?\.)+[a-z\u00C0-\u02FF\u0370-\u1EFF]([-0-9a-z\u00C0-\u02FF\u0370-\u1EFF]{0,17}[a-z\u00C0-\u02FF\u0370-\u1EFF])?$/i).test(val);
    },

    url: function (elem, arg, val, value) {
        if (!(/^[a-z\d+.-]+:/).test(val)) {
            val = 'http://' + val;
        }
        if ((/^https?:\/\/((([-_0-9a-z\u00C0-\u02FF\u0370-\u1EFF]+\.)*[0-9a-z\u00C0-\u02FF\u0370-\u1EFF]([-0-9a-z\u00C0-\u02FF\u0370-\u1EFF]{0,61}[0-9a-z\u00C0-\u02FF\u0370-\u1EFF])?\.)?[a-z\u00C0-\u02FF\u0370-\u1EFF]([-0-9a-z\u00C0-\u02FF\u0370-\u1EFF]{0,17}[a-z\u00C0-\u02FF\u0370-\u1EFF])?|\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}|\[[0-9a-f:]{3,39}\])(:\d{1,5})?(\/\S*)?$/i).test(val)) {
            value.value = val;
            return true;
        }
        return false;
    },

    regexp: function (elem, arg, val) {
        var parts = typeof arg === 'string' ? arg.match(/^\/(.*)\/([imu]*)$/) : false;
        try {
            return parts && (new RegExp(parts[1], parts[2].replace('u', ''))).test(val);
        } catch (e) {
        }
    },

    pattern: function (elem, arg, val) {
        try {
            return typeof arg === 'string' ? (new RegExp('^(' + arg + ')$')).test(val) : null;
        } catch (e) {
        }
    },

    integer: function (elem, arg, val) {
        return (/^-?[0-9]+$/).test(val);
    },

    'float': function (elem, arg, val, value) {
        val = val.replace(' ', '').replace(',', '.');
        if ((/^-?[0-9]*[.,]?[0-9]+$/).test(val)) {
            value.value = val;
            return true;
        }
        return false;
    },

    min: function (elem, arg, val) {
        return JssForm.validators.range(elem, [arg, null], val);
    },

    max: function (elem, arg, val) {
        return JssForm.validators.range(elem, [null, arg], val);
    },

    range: function (elem, arg, val) {
        return JssForm.isArray(arg) ?
            ((arg[0] === null || parseFloat(val) >= arg[0]) && (arg[1] === null || parseFloat(val) <= arg[1])) : null;
    },

    submitted: function (elem, arg, val) {
        return elem.form['JssForm-submittedBy'] === elem;
    },

    fileSize: function (elem, arg, val) {
        if (window.FileList) {
            for (var i = 0; i < val.length; i++) {
                if (val[i].size > arg) {
                    return false;
                }
            }
        }
        return true;
    },

    image: function (elem, arg, val) {
        if (window.FileList && val instanceof FileList) {
            for (var i = 0; i < val.length; i++) {
                var type = val[i].type;
                if (type && type !== 'image/gif' && type !== 'image/png' && type !== 'image/jpeg') {
                    return false;
                }
            }
        }
        return true;
    }
};


JssForm.parseJSON = function (s) {
    s = s || '[]';
    if (s.substr(0, 3) === '{op') {
        return eval('[' + s + ']'); // backward compatibility
    }
    return window.JSON && window.JSON.parse ? JSON.parse(s) : eval(s);
};

JssForm.isArray = function (arg) {
    return Object.prototype.toString.call(arg) === '[object Array]';
};

JssForm.inArray = function (arr, val) {
    if ([].indexOf) {
        return arr.indexOf(val) > -1;
    } else {
        for (var i = 0; i < arr.length; i++) {
            if (arr[i] === val) {
                return true;
            }
        }
        return false;
    }
};


JssForm.webalize = function (s) {
    s = s.toLowerCase();
    var res = '', i, ch;
    for (i = 0; i < s.length; i++) {
        ch = JssForm.webalizeTable[s.charAt(i)];
        res += ch ? ch : s.charAt(i);
    }
    return res.replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
};

JssForm.webalizeTable = {
    \u00e1: 'a',
    \u00e4: 'a',
    \u010d: 'c',
    \u010f: 'd',
    \u00e9: 'e',
    \u011b: 'e',
    \u00ed: 'i',
    \u013e: 'l',
    \u0148: 'n',
    \u00f3: 'o',
    \u00f4: 'o',
    \u0159: 'r',
    \u0161: 's',
    \u0165: 't',
    \u00fa: 'u',
    \u016f: 'u',
    \u00fd: 'y',
    \u017e: 'z'
};

JssForm.validateElement = function (element) {
    div = element.closest("div.form-group");
    div = element.closest("div.form-group");
    div.children(".help-block").remove();
    div.removeClass('has-error');
    vysledek = JssForm.validateControl(element, jQuery.parseJSON(element.attr('data-jssform-rules')), false);
    if (vysledek) {
        div.addClass('has-success');
    }
    return vysledek;
}

JssForm.validateForm = function (form) {
    has_error = false;
    form.find("[data-jssform-rules]").each(function () {
        if (!JssForm.validateElement($(this))) has_error = true;
    });
    return !has_error;
}

$(function () {
    $("[data-jssform-rules]").on('change blur', function () {
        JssForm.validateElement($(this));
    });
    $("form").submit(function (e) {
        if (!JssForm.validateForm($(this))) e.preventDefault();
    });
});