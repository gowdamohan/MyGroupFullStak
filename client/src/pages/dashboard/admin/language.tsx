import React from "react";
import DashboardLayout from "@/components/dashboard/DashboardLayout";
import { getActiveMenuItem } from "@/config/admin_menu";
import LanguageManagement from "@/components/content/LanguageManagement";

function LanguageManagementPage() {
  const menuItems = getActiveMenuItem('/dashboard/admin/language');

  return (
    <DashboardLayout
      title="Language Management"
      userRole="admin"
      menuItems={menuItems}
    >
      <LanguageManagement />
    </DashboardLayout>
  );
}

export default LanguageManagementPage;
