import React from "react";
import DashboardLayout from "@/components/dashboard/DashboardLayout";
import { getActiveMenuItem } from "@/config/admin_menu";
import ProfessionManagement from "@/components/content/ProfessionManagement";

function ProfessionManagementPage() {
  const menuItems = getActiveMenuItem('/dashboard/admin/profession');

  return (
    <DashboardLayout
      title="Profession Management"
      userRole="admin"
      menuItems={menuItems}
    >
      <ProfessionManagement />
    </DashboardLayout>
  );
}

export default ProfessionManagementPage;
