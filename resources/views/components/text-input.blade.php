@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'w-full rounded-lg border-slate-300 shadow-sm text-sm text-slate-800 focus:border-primary-500 focus:ring-primary-500 disabled:bg-slate-100 disabled:cursor-not-allowed transition duration-150']) }}>
