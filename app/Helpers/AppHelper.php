<?php

namespace App\Helpers;

class AppHelper
{
    public static function getExportRoute($feature)
    {
        return \App\Helpers\RouteHelper::route($feature . '.export.excel');
    }

    public static function getImportRoute($feature)
    {
        return \App\Helpers\RouteHelper::route($feature . '.import.excel');
    }

    public static function getTemplateRoute($feature)
    {
        return \App\Helpers\RouteHelper::route($feature . '.template.download');
    }

    public static function getIndexRoute($feature)
    {
        return \App\Helpers\RouteHelper::route($feature . '.index');
    }

    public static function getCreateRoute($feature)
    {
        return \App\Helpers\RouteHelper::route($feature . '.create');
    }

    public static function getEditRoute($feature, $id)
    {
        return \App\Helpers\RouteHelper::route($feature . '.edit', ['id' => $id]);
    }

    public static function getShowRoute($feature, $id)
    {
        return \App\Helpers\RouteHelper::route($feature . '.show', ['id' => $id]);
    }

    public static function getDestroyRoute($feature, $id)
    {
        return \App\Helpers\RouteHelper::route($feature . '.destroy', ['id' => $id]);
    }

    public static function getUpdateRoute($feature, $id)
    {
        return \App\Helpers\RouteHelper::route($feature . '.update', [$feature => $id]);
    }

    public static function getStoreRoute($feature)
    {
        return \App\Helpers\RouteHelper::route($feature . '.store');
    }

    public static function getBackButton($feature)
    {
        return '<a href="' . self::getIndexRoute($feature) . '" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>';
    }

    public static function getSubmitButton($text = 'Simpan')
    {
        return '<button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> ' . $text . '
                </button>';
    }

    public static function getActionButtons($feature, $id, $showEdit = true, $showDelete = true)
    {
        $buttons = '';
        
        if ($showEdit) {
            $buttons .= '<a href="' . self::getEditRoute($feature, $id) . '" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a> ';
        }
        
        if ($showDelete) {
            $buttons .= '<form action="' . self::getDestroyRoute($feature, $id) . '" method="POST" class="d-inline">
                            @csrf
                            @method("DELETE")
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin ingin menghapus data ini?\')">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </form>';
        }
        
        return $buttons;
    }

    public static function getExportButtons($feature)
    {
        return '<a href="' . self::getExportRoute($feature) . '" class="btn btn-success">
                    <i class="fas fa-file-excel"></i> Export Excel
                </a>
                <a href="' . \App\Helpers\RouteHelper::route($feature . '.export.pdf') . '" class="btn btn-danger">
                    <i class="fas fa-file-pdf"></i> Export PDF
                </a>';
    }

    public static function getImportButtons($feature)
    {
        return '<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#importModal">
                    <i class="fas fa-file-import"></i> Import Excel
                </button>
                <a href="' . self::getTemplateRoute($feature) . '" class="btn btn-info">
                    <i class="fas fa-download"></i> Download Template
                </a>';
    }
} 