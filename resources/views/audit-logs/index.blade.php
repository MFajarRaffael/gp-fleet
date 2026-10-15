@extends('adminlte::page')

@section('title', 'Riwayat Aktivitas')

@section('content_header')
<div class="audit-header">
    <div class="audit-heading">
        <div class="audit-heading-icon">
            <i class="fas fa-history"></i>
        </div>
        <div>
            <h1>Riwayat Aktivitas</h1>
            <p>Pantau aktivitas pengguna di sistem GP Fleet.</p>
        </div>
    </div>

    <div class="audit-header-label">
        <i class="fas fa-shield-alt"></i>
        Log Sistem
    </div>
</div>
@stop

@section('content')
<div class="audit-page">

    <div class="audit-summary">
        <div class="audit-summary-card">
            <div class="audit-summary-icon">
                <i class="fas fa-list-ul"></i>
            </div>
            <div>
                <span>Total Aktivitas</span>
                <h3>{{ number_format($logs->total()) }}</h3>
                <small>Seluruh catatan aktivitas</small>
            </div>
        </div>

        <div class="audit-summary-card">
            <div class="audit-summary-icon audit-icon-green">
                <i class="fas fa-calendar-day"></i>
            </div>
            <div>
                <span>Hari Ini</span>
                <h3>{{ number_format(\App\Models\AuditLog::whereDate('created_at', today())->count()) }}</h3>
                <small>Aktivitas tercatat hari ini</small>
            </div>
        </div>
    </div>

    <div class="audit-panel">
        <div class="audit-panel-header">
            <div>
                <h5>Daftar Aktivitas</h5>
                <p>Catatan perubahan data dan aktivitas pengguna.</p>
            </div>

            <span class="audit-total-badge">
                {{ number_format($logs->total()) }} catatan
            </span>
        </div>

        <form method="GET" action="{{ route('audit-logs.index') }}" class="audit-filter">
        
            <div class="audit-search">
                <i class="fas fa-search"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari nama pengguna atau aktivitas...">
            </div>
        
            <div class="audit-select">
                <select name="action">
                    <option value="">Semua aktivitas</option>
                    @foreach ($actions as $action)
                        <option value="{{ $action }}" {{ request('action') === $action ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_', ' ', $action)) }}
                        </option>
                    @endforeach
                </select>
            </div>
        
            <div class="audit-date">
                <label for="date_from">Dari tanggal</label>
                <input type="date" id="date_from" name="date_from" value="{{ request('date_from') }}">
            </div>
        
            <div class="audit-date">
                <label for="date_to">Sampai tanggal</label>
                <input type="date" id="date_to" name="date_to" value="{{ request('date_to') }}"
                    min="{{ request('date_from') }}">
            </div>
        
            <button type="submit" class="audit-search-btn">
                <i class="fas fa-filter"></i>
                Filter
            </button>
        
            @if(request()->hasAny(['search', 'action', 'date_from', 'date_to']))
                <a href="{{ route('audit-logs.index') }}" class="audit-reset-btn">
                    Reset
                </a>
            @endif
        </form>

        <div class="table-responsive">
            <table class="audit-table">
                <thead>
                    <tr>
                        <th>WAKTU</th>
                        <th>PENGGUNA</th>
                        <th>AKTIVITAS</th>
                        <th>DETAIL</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($logs as $log)
                        <tr>
                            <td>
                                <div class="audit-time">
                                    <span>{{ $log->created_at?->format('d M Y') ?? '-' }}</span>
                                    <small>
                                        <i class="far fa-clock"></i>
                                        {{ $log->created_at?->format('H:i') ?? '-' }}
                                    </small>
                                </div>
                            </td>

                            <td>
                                <div class="audit-user">
                                    <div class="audit-avatar">
                                        {{ strtoupper(substr($log->user_name ?: 'S', 0, 1)) }}
                                    </div>
                                    <span>{{ $log->user_name ?: 'Sistem' }}</span>
                                </div>
                            </td>

                            <td>
                                @php
    $actionLabel = str_replace('_', ' ', $log->action);
    $actionColor = str_ends_with($log->action, '_created')
        ? 'created'
        : (str_ends_with($log->action, '_updated')
            ? 'updated'
            : (str_ends_with($log->action, '_deleted')
                ? 'deleted'
                : 'default'));
                                @endphp

                                <span class="audit-action audit-action-{{ $actionColor }}">
                                    {{ ucfirst($actionLabel) }}
                                </span>
                            </td>

                            <td>
                                <div class="audit-detail">
                                    {{ $log->description ?: 'Tidak ada detail tambahan' }}
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">
                                <div class="audit-empty">
                                    <div class="audit-empty-icon">
                                        <i class="fas fa-clipboard-list"></i>
                                    </div>
                                    <h5>Belum Ada Aktivitas</h5>
                                    <p>
                                        @if(request()->hasAny(['search', 'action', 'date_from', 'date_to']))
                                            Tidak ada aktivitas yang sesuai dengan filter.
                                        @else
                                            Aktivitas pengguna akan muncul di sini setelah tercatat.
                                        @endif
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
            <div class="audit-pagination">
                <span>Menampilkan {{ $logs->firstItem() }}–{{ $logs->lastItem() }} dari {{ $logs->total() }} catatan</span>
                {{ $logs->links('pagination::bootstrap-4') }}
            </div>
        @endif
    </div>

    <div class="audit-footnote">
        <i class="fas fa-info-circle"></i>
        Riwayat digunakan untuk membantu memantau perubahan data di GP Fleet.
    </div>
</div>
@stop

@push('css')
    <link rel="stylesheet" href="{{ asset('css/audit-log.css') }}">
@endpush