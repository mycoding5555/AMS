@extends('layouts.admin')

@section('content')

<div style="background: linear-gradient(135deg, #f5f5f7 0%, #ffffff 100%); border-radius: 20px; padding: 32px; margin-bottom: 32px;">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1 style="font-size: 32px; font-weight: 600; color: #1d1d1f; margin: 0;">Edit Account Entry</h1>
            <p style="color: #86868b; margin: 8px 0 0 0;">Update transaction details</p>
        </div>
        <a href="{{ route('admin.expenses.index') }}" style="padding: 12px 24px; background: #f5f5f7; color: #1d1d1f; border-radius: 8px; text-decoration: none; font-weight: 600;">
            <i class="bi bi-arrow-left me-2"></i>Back to List
        </a>
    </div>
</div>

@if ($errors->any())
    <div style="background: #fef2f2; border: 1px solid #f87171; border-radius: 12px; padding: 16px; margin-bottom: 24px; color: #991b1b;">
        <ul style="margin: 0; padding-left: 20px;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div style="background: white; border: 1px solid #d5d5d7; border-radius: 12px; padding: 32px;">
    <form method="POST" action="{{ route('admin.expenses.update', $account) }}">
        @csrf
        @method('PUT')

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px;">
            {{-- Account Type --}}
            <div>
                <label style="display: block; color: #86868b; font-size: 13px; font-weight: 600; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;">
                    Account Type *
                </label>
                <select name="account_type" id="account_type" required
                    style="width: 100%; padding: 12px; border: 1px solid #d5d5d7; border-radius: 8px; background: white; font-size: 14px;">
                    <option value="income" {{ old('account_type', $account->account_type) == 'income' ? 'selected' : '' }}>Income</option>
                    <option value="expense" {{ old('account_type', $account->account_type) == 'expense' ? 'selected' : '' }}>Expense</option>
                </select>
            </div>

            {{-- Cost Type --}}
            <div>
                <label style="display: block; color: #86868b; font-size: 13px; font-weight: 600; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;">
                    Cost Type *
                </label>
                <select name="cost_type" id="cost_type" required
                    style="width: 100%; padding: 12px; border: 1px solid #d5d5d7; border-radius: 8px; background: white; font-size: 14px;">
                    <option value="income" {{ old('cost_type', $account->cost_type) == 'income' ? 'selected' : '' }}>Income</option>
                    <option value="fixed" {{ old('cost_type', $account->cost_type) == 'fixed' ? 'selected' : '' }}>Fixed Cost</option>
                    <option value="variable" {{ old('cost_type', $account->cost_type) == 'variable' ? 'selected' : '' }}>Variable Cost</option>
                    <option value="bank" {{ old('cost_type', $account->cost_type) == 'bank' ? 'selected' : '' }}>Bank/Financial</option>
                </select>
            </div>

            {{-- Category --}}
            <div>
                <label style="display: block; color: #86868b; font-size: 13px; font-weight: 600; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;">
                    Category *
                </label>
                <select name="category" id="category" required
                    style="width: 100%; padding: 12px; border: 1px solid #d5d5d7; border-radius: 8px; background: white; font-size: 14px;">
                    @foreach($categories as $key => $label)
                        <option value="{{ $key }}" {{ old('category', $account->category) == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Amount --}}
            <div>
                <label style="display: block; color: #86868b; font-size: 13px; font-weight: 600; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;">
                    Amount ($) *
                </label>
                <input type="number" name="amount" step="0.01" min="0.01" value="{{ old('amount', $account->amount) }}" required
                    style="width: 100%; padding: 12px; border: 1px solid #d5d5d7; border-radius: 8px; font-size: 14px;"
                    placeholder="0.00">
            </div>

            {{-- Transaction Date --}}
            <div>
                <label style="display: block; color: #86868b; font-size: 13px; font-weight: 600; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;">
                    Transaction Date *
                </label>
                <input type="date" name="transaction_date" value="{{ old('transaction_date', $account->transaction_date->format('Y-m-d')) }}" required
                    style="width: 100%; padding: 12px; border: 1px solid #d5d5d7; border-radius: 8px; font-size: 14px;">
            </div>

            {{-- Reference Number --}}
            <div>
                <label style="display: block; color: #86868b; font-size: 13px; font-weight: 600; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;">
                    Reference Number
                </label>
                <input type="text" name="reference_number" value="{{ old('reference_number', $account->reference_number) }}"
                    style="width: 100%; padding: 12px; border: 1px solid #d5d5d7; border-radius: 8px; font-size: 14px;"
                    placeholder="e.g., INV-001, TXN-12345">
            </div>

            {{-- Description --}}
            <div style="grid-column: 1 / -1;">
                <label style="display: block; color: #86868b; font-size: 13px; font-weight: 600; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;">
                    Description
                </label>
                <input type="text" name="description" value="{{ old('description', $account->description) }}"
                    style="width: 100%; padding: 12px; border: 1px solid #d5d5d7; border-radius: 8px; font-size: 14px;"
                    placeholder="Brief description of the transaction">
            </div>

            {{-- Notes --}}
            <div style="grid-column: 1 / -1;">
                <label style="display: block; color: #86868b; font-size: 13px; font-weight: 600; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;">
                    Notes
                </label>
                <textarea name="notes" rows="3"
                    style="width: 100%; padding: 12px; border: 1px solid #d5d5d7; border-radius: 8px; font-size: 14px; resize: vertical;"
                    placeholder="Additional notes...">{{ old('notes', $account->notes) }}</textarea>
            </div>
        </div>

        <div style="margin-top: 32px; display: flex; gap: 12px;">
            <button type="submit" style="padding: 14px 32px; background: #0071e3; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 15px;">
                Update Entry
            </button>
            <a href="{{ route('admin.expenses.index') }}" style="padding: 14px 32px; background: #f5f5f7; color: #1d1d1f; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 15px;">
                Cancel
            </a>
        </div>
    </form>
</div>

@endsection
