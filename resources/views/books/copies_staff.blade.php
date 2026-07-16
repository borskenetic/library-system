@extends('layouts.sec')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/books/copies_staff.css') }}">
@endsection

@section('content')
<div class="book-copies-page">
    <div class="book-copies__toolbar">
        <a href="{{ route('book.index') }}" class="btn btn-outline-secondary btn-sm">← Catalog</a>
        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-sm">Previous page</a>
    </div>

    <header class="book-copies__hero">
        <div class="book-copies__cover-card">
            <a href="{{ $coverUrl }}" target="_blank" rel="noopener noreferrer" class="book-copies__cover-link">
                <img src="{{ $coverUrl }}" alt="Cover of {{ $title }}" class="book-copies__cover-img">
            </a>
        </div>

        <div class="book-copies__meta">
            <p class="book-copies__eyebrow">Copy inventory</p>
            <h1 class="book-copies__title">{{ $title }}</h1>
            <p class="book-copies__subtitle">{{ $author }} — {{ $year }}</p>

            <div class="book-copies__stats">
                <span class="book-copies__stat book-copies__stat--total">
                    {{ $totalCopies }} {{ $totalCopies === 1 ? 'copy' : 'copies' }}
                </span>
                <span class="book-copies__stat book-copies__stat--available">
                    {{ $availableCopies }} available
                </span>
                @if($borrowedCopies > 0)
                    <span class="book-copies__stat book-copies__stat--borrowed">
                        {{ $borrowedCopies }} borrowed
                    </span>
                @endif
            </div>
        </div>
    </header>

    <section class="book-copies__card">
        <div class="book-copies__card-head">
            <h2 class="book-copies__card-title">All copies</h2>
            <p class="book-copies__card-hint">
                Showing {{ $copies->count() }} of {{ $copies->total() }}
            </p>
        </div>

        <div class="book-copies__table-wrap">
            <table class="table table-hover book-copies__table mb-0">
                <thead>
                    <tr>
                        <th>Accession no.</th>
                        <th>Barcode</th>
                        <th>RFID</th>
                        <th>Status</th>
                        <th>Date added</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($copies as $copy)
                        <tr>
                            <td>
                                @if(filled($copy->accession_no))
                                    <code class="book-copies__code">{{ $copy->accession_no }}</code>
                                @else
                                    <span class="book-copies__empty">—</span>
                                @endif
                            </td>
                            <td>
                                @if(filled($copy->barcode))
                                    <code class="book-copies__code">{{ $copy->barcode }}</code>
                                @else
                                    <span class="book-copies__empty">—</span>
                                @endif
                            </td>
                            <td>
                                @if(filled($copy->rfid))
                                    <code class="book-copies__code">{{ $copy->rfid }}</code>
                                @else
                                    <span class="book-copies__empty">—</span>
                                @endif
                            </td>
                            <td>
                                @if($copy->availability === 'Available')
                                    <span class="book-copies__badge book-copies__badge--available">Available</span>
                                @else
                                    <span class="book-copies__badge book-copies__badge--borrowed">{{ $copy->availability }}</span>
                                @endif
                            </td>
                            <td class="text-nowrap">{{ $copy->created_at?->format('Y-m-d') ?? '—' }}</td>
                            <td class="text-end">
                                <div class="book-copies__actions">
                                    <a href="{{ route('book.show', $copy->id) }}" class="btn btn-outline-secondary btn-sm">View</a>
                                    <a href="{{ route('book.edit', $copy->id) }}" class="btn btn-outline-primary btn-sm">Edit</a>
                                    <button type="button" class="btn btn-outline-danger btn-sm"
                                        data-bs-toggle="modal" data-bs-target="#deleteModal{{ $copy->id }}">
                                        Delete
                                    </button>
                                </div>

                                <div class="modal fade" id="deleteModal{{ $copy->id }}" tabindex="-1"
                                    aria-labelledby="deleteModalLabel{{ $copy->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content rounded-3 shadow-lg">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title" id="deleteModalLabel{{ $copy->id }}">Confirm delete</h5>
                                                <button type="button" class="btn-close btn-close-white"
                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Move <strong>{{ $copy->title_statement }}</strong> to trash? You can restore it later.
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Cancel</button>
                                                <form action="{{ route('books.destroy', $copy->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">No copies found for this title.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="book-copies__footer">
            @include('layouts.partials.pagination_bar', ['paginator' => $copies])
        </div>
    </section>
</div>
@endsection
