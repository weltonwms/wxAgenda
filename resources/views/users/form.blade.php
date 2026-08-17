@php
    $formModel = null;
    if (isset($student)) {
        $formModel = $student;
    } elseif (isset($teacher)) {
        $formModel = $teacher;
    } elseif (isset($administrator)) {
        $formModel = $administrator;
    } elseif (isset($user)) {
        $formModel = $user;
    }
@endphp

<x-form.text name="username" :value="optional($formModel)->username" label="Username *" />
<x-form.password name="password" :label="isset($user) ? 'Senha' : 'Senha '" />
